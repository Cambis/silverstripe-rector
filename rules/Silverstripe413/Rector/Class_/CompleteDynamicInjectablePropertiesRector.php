<?php

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\NodeAnalyzer\SilverstripeAnalyzer;
use Cambis\SilverstripeRector\Rector\AbstractAPIAwareRector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\PostRector\ValueObject\PropertyMetadata;
use SilverStripe\Core\Injector\Injectable;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_keys;

/**
 * @see Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\CompleteDynamicInjectablePropertiesRectorTest
 */
final class CompleteDynamicInjectablePropertiesRector extends AbstractAPIAwareRector
{
    public function __construct(
        private readonly SilverstripeAnalyzer $silverstripeAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly MissingPropertiesFactory $missingPropertiesFactory,
        private readonly PropertyPresenceChecker $propertyPresenceChecker
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic properties.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $dependencies = [
        'bar' => '%$' . Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    /**
     * @var Bar
     */
    public $bar;

    private static array $dependencies = [
        'bar' => '%$' . Bar::class,
    ];
}
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactorAPIAwareNode(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $dependencyProperties = $this->silverstripeAnalyzer->extractPropertyTypesFromDependencies($classConst);
        $propertiesToComplete = $this->filterOutExistingProperties(
            $node,
            $classReflection,
            array_keys($dependencyProperties)
        );

        $newProperties = $this->missingPropertiesFactory->create($dependencyProperties, $propertiesToComplete);

        if ($newProperties === []) {
            return null;
        }

        $node->stmts = [...$newProperties, ...$node->stmts];

        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $className = $this->nodeNameResolver->getName($class);

        if ($className === null) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return !$classReflection->hasTraitUse(Injectable::class);
    }

    /**
     * @param string[] $propertiesToComplete
     * @return string[]
     */
    private function filterOutExistingProperties(
        Class_ $class,
        ClassReflection $classReflection,
        array $propertiesToComplete
    ): array {
        $missingPropertyNames = [];
        $className = $classReflection->getName();
        // remove other properties that are accessible from this scope
        foreach ($propertiesToComplete as $propertyToComplete) {
            if ($classReflection->hasProperty($propertyToComplete)) {
                continue;
            }

            $propertyMetadata = new PropertyMetadata($propertyToComplete, new ObjectType($className));
            $hasClassContextProperty = $this->propertyPresenceChecker->hasClassContextProperty(
                $class,
                $propertyMetadata
            );

            if ($hasClassContextProperty) {
                continue;
            }

            $missingPropertyNames[] = $propertyToComplete;
        }

        return $missingPropertyNames;
    }
}
