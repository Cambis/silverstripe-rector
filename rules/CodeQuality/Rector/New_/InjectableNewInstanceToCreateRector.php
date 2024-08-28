<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\CodeQuality\Rector\New_;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use SilverStripe\Core\Injector\Injectable;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\InjectableNewInstanceToCreateRectorTest
 */
final class InjectableNewInstanceToCreateRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ClassAnalyzer $classAnalyzer;
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    public function __construct(ClassAnalyzer $classAnalyzer, ReflectionProvider $reflectionProvider)
    {
        $this->classAnalyzer = $classAnalyzer;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change `new Injectable()` to use Injectable::create() instead.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$foo = new \SilverStripe\ORM\ArrayList();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$foo = \SilverStripe\ORM\ArrayList::create();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipNew($node)) {
            return null;
        }
        $className = (string) $this->nodeNameResolver->getName($node->class);
        return $this->nodeFactory->createStaticCall(
            $className,
            SilverstripeConstants::METHOD_CREATE,
            $node->args
        );
    }

    private function shouldSkipNew(New_ $new): bool
    {
        $className = (string) $this->nodeNameResolver->getName($new->class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($this->classAnalyzer->isAnonymousClass($new->class)) {
            return true;
        }

        if (!$classReflection->hasTraitUse(Injectable::class)) {
            return true;
        }

        return !$classReflection->hasMethod(MethodName::TO_STRING);
    }
}
