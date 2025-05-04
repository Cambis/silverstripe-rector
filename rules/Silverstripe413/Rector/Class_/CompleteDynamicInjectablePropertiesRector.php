<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_keys;

/**
 * @see Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\CompleteDynamicInjectablePropertiesRectorTest
 */
final class CompleteDynamicInjectablePropertiesRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    /**
     * @readonly
     */
    private ClassAnalyser $classAnalyser;
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
    public function __construct(ClassAnalyser $classAnalyser, MissingPropertiesFactory $missingPropertiesFactory, PropertyPresenceChecker $propertyPresenceChecker, ReflectionProvider $reflectionProvider, TypeResolver $typeResolver)
    {
        $this->classAnalyser = $classAnalyser;
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
        if (!$this->classAnalyser->isInjectable($node)) {
            return null;
        }
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        /** @var array<string, Type> $dependencyProperties */
        $dependencyProperties = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty($classReflection, 'dependencies');
        $propertiesToComplete = $this->filterOutExistingProperties(
            $node,
            $classReflection,
            array_keys($dependencyProperties)
        );
        $newProperties = $this->missingPropertiesFactory->create($dependencyProperties, $propertiesToComplete);
        if ($newProperties === []) {
            return null;
        }
        $node->stmts = array_merge($newProperties, $node->stmts);
        return $node;
    }

    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
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
