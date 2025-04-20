<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_keys;

/**
 * @see Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\CompleteDynamicInjectablePropertiesRectorTest
 */
final class CompleteDynamicInjectablePropertiesRector extends AbstractRector implements RelatedConfigInterface
{
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
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    private TypeResolver $typeResolver;
    public function __construct(ClassAnalyzer $classAnalyzer, MissingPropertiesFactory $missingPropertiesFactory, PropertyPresenceChecker $propertyPresenceChecker, ReflectionProvider $reflectionProvider, TypeResolver $typeResolver)
    {
        $this->classAnalyzer = $classAnalyzer;
        $this->missingPropertiesFactory = $missingPropertiesFactory;
        $this->propertyPresenceChecker = $propertyPresenceChecker;
        $this->reflectionProvider = $reflectionProvider;
        $this->typeResolver = $typeResolver;
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
        $dependencyProperties = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty($classReflection, SilverstripeConstants::PROPERTY_DEPENDENCIES);
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

    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
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

        return !$classReflection->hasTraitUse('SilverStripe\Core\Injector\Injectable');
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
