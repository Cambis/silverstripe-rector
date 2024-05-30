<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe60\Rector\New_;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Nop;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use SilverStripe\Control\Session;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_filter;
use function count;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\New_\SilverstripeSessionToSymfonySessionRector\SilverstripeSessionToSymfonySessionRectorTest
 */
final class SilverstripeSessionToSymfonySessionRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly VariableNaming $variableNaming,
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate from \SilverStripe\Control\Session to \Symfony\Component\HttpFoundation\Session\Session.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo
{
    private $session;

    public function __construct()
    {
        $config = ['something' => ['some' => 'value', 'another' => 'item']];

        $this->session = new \SilverStripe\Control\Session($config);
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class Foo
{
    private $session;

    public function __construct()
    {
        $config = ['something' => ['some' => 'value', 'another' => 'item']];

        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();

        foreach ($config as $sessionKey => $sessionValue) {
            $this->session->set($sessionKey, $sessionValue);
        }
    }
}
CODE_SAMPLE
        ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        $instances = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped(
            $node,
            [New_::class]
        );

        // These are the nodes that will be removed
        $nodesToRemove = [];

        // These are the nodes that we will add
        $nodesToAdd = [];

        foreach ($instances as $instance) {
            if ($this->shouldSkipNew($instance)) {
                continue;
            }

            $hasChanged = true;

            // Keep an unmodified reference to the original node
            $originalInstance = clone $instance;

            // This will be our new node
            $newInstance = clone $instance;
            $newInstance->class = new FullyQualified('Symfony\\Component\\HttpFoundation\\Session\\Session');
            $newInstance->args = [];

            $nodesToRemove[] = new Expression($originalInstance);

            // This is a reference used for the assign statement
            $expr = null;

            if ($originalInstance->getAttribute(AttributeKey::IS_ASSIGNED_TO) === true) {
                // If the original instance is already assigned, we want to retain the original variable name
                $assign = $this->betterNodeFinder->findFirst((array) $node->stmts, function (Node $node) use ($originalInstance): bool {
                    return $node instanceof Assign && $this->nodeComparator->areNodesEqual($node->expr, $originalInstance);
                });

                if (!$assign instanceof Assign) {
                    continue;
                }

                if (!$assign->var instanceof Variable && !$assign->var instanceof PropertyFetch && !$assign->var instanceof StaticPropertyFetch) {
                    continue;
                }

                // Remove the original assign statement
                $nodesToRemove[] = new Expression($assign);

                $newExpr = clone $newInstance;
                $newInstance = clone $assign;
                $newInstance->expr = $newExpr;

                $expr = clone $assign->var;
            } else {
                // If there was no assign, then let's create a new variable for the session
                $variableName = $this->variableNaming->createCountedValueName('session', $node->getAttribute(AttributeKey::SCOPE));

                $newInstance = new Assign(
                    new Variable($variableName),
                    new New_(new FullyQualified('Symfony\\Component\\HttpFoundation\\Session\\Session'))
                );

                $expr = new Variable($variableName);
            }

            $nodesToAdd[] = new Expression($newInstance);

            if (count($originalInstance->getArgs()) !== 1) {
                continue;
            }

            $constructorArg = $originalInstance->getArgs()[0]->value;
            $constructorArgType = $this->nodeTypeResolver->getNativeType($constructorArg);

            // If an array was passed as a constructor argument, loop over the array and call `$session->set()`
            if ($constructorArgType->isArray()->no()) {
                continue;
            }

            $nodesToAdd[] = new Nop();
            $nodesToAdd[] = new Foreach_(
                $constructorArg,
                new Variable('sessionValue'),
                [
                    'keyVar' => new Variable('sessionKey'),
                    'stmts' => [
                        new Expression(
                            $this->nodeFactory->createMethodCall($expr, 'set', [new Variable('sessionKey'), new Variable('sessionValue')])
                        ),
                    ],
                ]
            );
        }

        if (!$hasChanged) {
            return null;
        }

        // Remove any unwanted nodes
        $node->stmts = array_filter((array) $node->stmts, function (Node $node) use ($nodesToRemove): bool {
            foreach ($nodesToRemove as $nodeToRemove) {
                if ($this->nodeComparator->areNodesEqual($node, $nodeToRemove)) {
                    return false;
                }
            }

            return true;
        });

        // Add an extra newline there are other nodes above
        if ($node->stmts !== []) {
            $node->stmts[] = new Nop();
        }

        $node->stmts = [...$node->stmts, ...$nodesToAdd];

        return $node;
    }

    private function shouldSkipNew(New_ $new): bool
    {
        if ($new->isFirstClassCallable()) {
            return true;
        }

        $className = (string) $this->nodeNameResolver->getName($new->class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($this->classAnalyzer->isAnonymousClass($new->class)) {
            return true;
        }

        return !$classReflection->is(Session::class);
    }
}
