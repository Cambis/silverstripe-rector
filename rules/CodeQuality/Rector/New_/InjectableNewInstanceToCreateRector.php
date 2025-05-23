<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\CodeQuality\Rector\New_;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\InjectableNewInstanceToCreateRectorTest
 */
final class InjectableNewInstanceToCreateRector extends AbstractRector implements DocumentedRuleInterface
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    #[Override]
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
    #[Override]
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipNew($node)) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($node->class);

        return $this->nodeFactory->createStaticCall(
            $className,
            'create',
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

        if (!$classReflection->hasTraitUse('SilverStripe\Core\Injector\Injectable')) {
            return true;
        }

        return !$classReflection->hasMethod(MethodName::TO_STRING);
    }
}
