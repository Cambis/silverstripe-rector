<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\Class_;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\NodeFactory\PropertyFactory;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function in_array;
use function is_array;
use function is_string;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/02_gorriecoe-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\GorriecoeLinkToSilverstripeLinkRectorTest
 */
final class GorriecoeLinkToSilverstripeLinkRector extends AbstractRector implements RelatedConfigInterface
{
    private bool $hasChanged = false;

    public function __construct(
        private readonly ClassAnalyser $classAnalyser,
        private readonly ConfigurationResolver $configurationResolver,
        private readonly PropertyFactory $propertyFactory,
        private readonly ValueResolver $valueResolver
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `gorriecoe\Link\Models\Link` configuration to `SilverStripe\LinkField\Models\Link` configuration.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'HasOneLink' => \gorriecoe\Link\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \gorriecoe\Link\Models\Link::class,
    ];

    private static array $many_many = [
        'ManyManyLinks' => \gorriecoe\Link\Models\Link::class,
    ];

    private static array $many_many_extraFields = [
        'ManyManyLinks' => [
            'Sort' => 'Int',
        ],
    ];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
        'ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];

    private static array $owns = [
        'HasOneLink',
        'HasManyLinks',
        'ManyManyLinks',
    ];
}
CODE_SAMPLE
            ),
        ]);
    }

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
        if (!$this->classAnalyser->isDataObject($node)) {
            return null;
        }

        $hasOne = $this->propertyFactory->findConfigurationProperty($node, SilverstripeConstants::PROPERTY_HAS_ONE);

        // Migrate has_one configuration
        if ($hasOne instanceof Property) {
            $node = $this->refactorHasOne($node, $hasOne);
        }

        $manyMany = $this->propertyFactory->findConfigurationProperty($node, SilverstripeConstants::PROPERTY_MANY_MANY);

        // Migrate many_many to has_many
        if ($manyMany instanceof Property) {
            $node = $this->refactorManyMany($node, $manyMany);
        }

        $hasMany = $this->propertyFactory->findConfigurationProperty($node, SilverstripeConstants::PROPERTY_HAS_MANY);

        // Migrate has_many configuration
        if ($hasMany instanceof Property) {
            $node = $this->refactorHasMany($node, $hasMany);
        }

        if (!$this->hasChanged) {
            return null;
        }

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    private function refactorHasOne(Class_ $class, Property $property): Class_
    {
        $value = $property->props[0]->default;

        if (!$value instanceof Array_) {
            return $class;
        }

        foreach ($value->items as $item) {
            // Safety checks...
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if ($this->shouldSkipArrayItem($item)) {
                continue;
            }

            // Name of the new link class
            $newValue = new ClassConstFetch(new FullyQualified('SilverStripe\LinkField\Models\Link'), 'class');

            // Check for existing dot notation
            if ($item->value instanceof Concat) {
                $newValue = $this->nodeFactory->createConcat([
                    new ClassConstFetch(new FullyQualified('SilverStripe\LinkField\Models\Link'), 'class'),
                    $item->value->right,
                ]);
            }

            // Safety check
            if (!$newValue instanceof Expr) {
                continue;
            }

            // Rename the link class
            $item->value = $newValue;

            $this->hasChanged = true;

            if (!$item->key instanceof Expr) {
                continue;
            }

            $memberName = $this->valueResolver->getValue($item->key);

            if (!is_string($memberName)) {
                continue;
            }

            // Add this member to the owns configuration
            if ($this->shouldAddMemberToOwns($class)) {
                $this->addMemberToOwns($class, $memberName);
            }
        }

        return $class;
    }

    private function refactorManyMany(Class_ $class, Property $property): Class_
    {
        $value = $property->props[0]->default;

        // Safety check
        if (!$value instanceof Array_) {
            return $class;
        }

        foreach ($value->items as $key => $item) {
            // Safety check
            if (!$item instanceof ArrayItem) {
                continue;
            }

            // Not a gorriecoe link, skip
            if ($this->shouldSkipArrayItem($item)) {
                continue;
            }

            // Add this member to the has_many property
            $this->addMemberToHasMany($class, $item);

            // Remove this member from the many_many_extraFields property
            $this->removeMemberFromManyManyExtraFields($class, $item);

            // Remove this member from the property
            unset($value->items[$key]);

            $this->hasChanged = true;
        }

        // If many_many is empty, remove it
        if ($value->items === []) {
            $this->removeProperty($class, $this->getName($property));
        }

        return $class;
    }

    private function addMemberToHasMany(Class_ $class, ArrayItem $arrayItem): void
    {
        $hasMany = $this->propertyFactory->findConfigurationProperty($class, SilverstripeConstants::PROPERTY_HAS_MANY);

        if (!$hasMany instanceof Property) {
            $hasMany = $this->propertyFactory->createArrayConfigurationProperty($class, SilverstripeConstants::PROPERTY_HAS_MANY);
            $hasMany->props[0]->default = new Array_([]);
        }

        $value = $hasMany->props[0]->default;

        if (!$value instanceof Array_) {
            return;
        }

        $value->items[] = $arrayItem;
    }

    private function removeMemberFromManyManyExtraFields(Class_ $class, ArrayItem $arrayItem): void
    {
        // Skip if there is no key
        if (!$arrayItem->key instanceof Expr) {
            return;
        }

        $manyManyExtraFields = $this->propertyFactory->findConfigurationProperty($class, SilverstripeConstants::PROPERTY_MANY_MANY_EXTRA_FIELDS);

        // Skip if there is no property
        if (!$manyManyExtraFields instanceof Property) {
            return;
        }

        $value = $manyManyExtraFields->props[0]->default;

        // Safety check
        if (!$value instanceof Array_) {
            return;
        }

        foreach ($value->items as $key => $item) {
            // Safety checks...
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if (!$item->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->getValue($item->key) !== $this->valueResolver->getValue($arrayItem->key)) {
                continue;
            }

            // Remove this member from the property
            unset($value->items[$key]);
        }

        // If the property is empty, remove it
        if ($value->items === []) {
            $this->removeProperty($class, $this->getName($manyManyExtraFields));
        }
    }

    /**
     * Skip if the array item does not reference `gorriecoe\Link\Models\Link`.
     */
    private function shouldSkipArrayItem(ArrayItem $arrayItem): bool
    {
        $value = $arrayItem->value;

        if ($value instanceof Concat) {
            $value = $value->left;
        }

        if ($value instanceof ClassConstFetch) {
            return !$this->isName($value->class, 'gorriecoe\Link\Models\Link');
        }

        return true;
    }

    private function refactorHasMany(Class_ $class, Property $property): Class_
    {
        $value = $property->props[0]->default;

        if (!$value instanceof Array_) {
            return $class;
        }

        foreach ($value->items as $item) {
            // Safety checks...
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if ($this->shouldSkipArrayItem($item)) {
                continue;
            }

            // New link class
            $newValue = $this->nodeFactory->createConcat([
                new ClassConstFetch(new FullyQualified('SilverStripe\LinkField\Models\Link'), 'class'),
                new String_('.Owner'),
            ]);

            // Safety check
            if (!$newValue instanceof Concat) {
                continue;
            }

            $item->value = $newValue;

            $this->hasChanged = true;

            if (!$item->key instanceof Expr) {
                continue;
            }

            $memberName = $this->valueResolver->getValue($item->key);

            if (!is_string($memberName)) {
                continue;
            }

            // Add this member to the owns configuration
            if ($this->shouldAddMemberToOwns($class)) {
                $this->addMemberToOwns($class, $memberName);
            }
        }

        return $class;
    }

    /**
     * Return false if the class is in `SilverStripe\LinkField\Tasks\GorriecoeMigrationTask::$classes_that_are_not_link_owners`.
     */
    private function shouldAddMemberToOwns(Class_ $class): bool
    {
        $notOwners = $this->configurationResolver->get('SilverStripe\LinkField\Tasks\GorriecoeMigrationTask', 'classes_that_are_not_link_owners');

        if (!is_array($notOwners) || $notOwners === []) {
            return true;
        }

        return !in_array($this->getName($class), $notOwners, true);
    }

    private function addMemberToOwns(Class_ $class, string $memberName): void
    {
        $owns = $this->propertyFactory->findConfigurationProperty($class, SilverstripeConstants::PROPERTY_OWNS);

        if (!$owns instanceof Property) {
            $owns = $this->propertyFactory->createArrayConfigurationProperty($class, SilverstripeConstants::PROPERTY_OWNS);
            $owns->props[0]->default = new Array_([]);
        }

        $value = $owns->props[0]->default;

        // Best not to modify in order to avoid unexpected errors
        if (!$value instanceof Array_) {
            return;
        }

        // Resolve the existing members
        $resolvedValue = $this->valueResolver->getValue($value);

        // Skip if unresolved
        if (!is_array($resolvedValue)) {
            return;
        }

        // Check that the member isn't already in the array
        if (in_array($memberName, $resolvedValue, true)) {
            return;
        }

        // Add the member to the array
        $value->items[] = new ArrayItem(new String_($memberName));

        $this->hasChanged = true;
    }

    private function removeProperty(Class_ $class, string $propertyName): void
    {
        foreach ($class->stmts as $key => $stmt) {
            if (!$stmt instanceof Property) {
                continue;
            }

            if (!$this->isName($stmt, $propertyName)) {
                continue;
            }

            unset($class->stmts[$key]);
        }
    }
}
