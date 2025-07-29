<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\CodeQuality\ValueObject\DefinedPropertyWithType;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\CompleteDynamicInjectablePropertiesRectorTest
 */
final class CompleteDynamicInjectablePropertiesRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    public function __construct(
        private readonly ClassAnalyser $classAnalyser,
        private readonly MissingPropertiesFactory $missingPropertiesFactory,
        private readonly PropertyPresenceChecker $propertyPresenceChecker,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly TypeResolver $typeResolver
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
            $dependencyProperties
        );

        $newProperties = $this->missingPropertiesFactory->create($propertiesToComplete);

        if ($newProperties === []) {
            return null;
        }

        $node->stmts = [...$newProperties, ...$node->stmts];

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * @param Type[] $propertiesToComplete
     * @return DefinedPropertyWithType[]
     */
    private function filterOutExistingProperties(
        Class_ $class,
        ClassReflection $classReflection,
        array $propertiesToComplete
    ): array {
        $missingProperties = [];
        // remove other properties that are accessible from this scope
        foreach ($propertiesToComplete as $propertyName => $propertyToComplete) {
            if ($classReflection->hasProperty($propertyName)) {
                continue;
            }

            $propertyMetadata = new DefinedPropertyWithType($propertyName, $propertyToComplete, null);
            $hasClassContextProperty = $this->propertyPresenceChecker->hasClassContextProperty(
                $class,
                $propertyMetadata
            );

            if ($hasClassContextProperty) {
                continue;
            }

            $missingProperties[] = $propertyMetadata;
        }

        return $missingProperties;
    }
}
