<?php

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use SilverStripe\Core\Injector\Injectable;
use SilverstripeRector\NodeAnalyzer\SilverstripeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_keys;

/**
 * @see SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\CompleteDynamicInjectablePropertiesRectorTest
 */
final class CompleteDynamicInjectablePropertiesRector extends AbstractRector
{
    /**
     * @readonly
     */
    private SilverstripeAnalyzer $silverstripeAnalyzer;
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    private ClassAnalyzer $classAnalyzer;
    /**
     * @readonly
     */
    private MissingPropertiesFactory $missingPropertiesFactory;
    /**
     * @readonly
     */
    private PropertyPresenceChecker $propertyPresenceChecker;
    public function __construct(SilverstripeAnalyzer $silverstripeAnalyzer, ReflectionProvider $reflectionProvider, ClassAnalyzer $classAnalyzer, MissingPropertiesFactory $missingPropertiesFactory, PropertyPresenceChecker $propertyPresenceChecker)
    {
        $this->silverstripeAnalyzer = $silverstripeAnalyzer;
        $this->reflectionProvider = $reflectionProvider;
        $this->classAnalyzer = $classAnalyzer;
        $this->missingPropertiesFactory = $missingPropertiesFactory;
        $this->propertyPresenceChecker = $propertyPresenceChecker;
    }

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
        $node->stmts = array_merge(is_array($newProperties) ? $newProperties : iterator_to_array($newProperties), $node->stmts);
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
