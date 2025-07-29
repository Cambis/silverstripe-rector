<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe51\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector\RenameEnabledToIsEnabledOnBuildTaskRectorTest
 * @changelog https://docs.silverstripe.org/en/5/changelogs/5.1.0/#api-changes-framework
 */
final class RenameEnabledToIsEnabledOnBuildTaskRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    private VisibilityManipulator $visibilityManipulator;
    public function __construct(ReflectionProvider $reflectionProvider, VisibilityManipulator $visibilityManipulator)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->visibilityManipulator = $visibilityManipulator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename protected property $enabled to configurable property $is_enabled.', [new CodeSample(
            <<<'CODE_SAMPLE'
class FooTask extends \SilverStripe\Dev\BuildTask
{
    protected $enabled = true;
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class FooTask extends \SilverStripe\Dev\BuildTask
{
    private static bool $is_enabled = true;
}
CODE_SAMPLE
        ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }
        $property = $node->getProperty('enabled');
        if (!$property instanceof Property) {
            return null;
        }
        $property->props[0]->name = new VarLikeIdentifier('is_enabled');
        $property->type = new Identifier('bool');
        $this->visibilityManipulator->makePrivate($property);
        $this->visibilityManipulator->makeStatic($property);
        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        $className = $this->nodeNameResolver->getName($class);

        if ($className === null) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return !$classReflection->is('SilverStripe\Dev\BuildTask');
    }
}
