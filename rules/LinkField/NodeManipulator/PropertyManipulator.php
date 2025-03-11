<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\NodeManipulator;

use Cambis\SilverstripeRector\NodeFactory\PropertyFactory;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\PhpParser\Node\Value\ValueResolver;
use function in_array;
use function is_array;
use function is_string;

final readonly class PropertyManipulator
{
    public function __construct(
        private NodeFactory $nodeFactory,
        private NodeNameResolver $nodeNameResolver,
        private PropertyFactory $propertyFactory,
        private ValueResolver $valueResolver
    ) {
    }

    /**
     * Refactor `$has_one` relation by renaming instances of `$legacyLinkClassName` to `SilverStripe\LinkField\Models\Link`.
     */
    public function refactorHasOne(Class_ $class, Property $property, string $legacyLinkClassName, bool $shouldAddMemberToOwns, bool &$hasChanged): Class_
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

            if ($this->shouldSkipArrayItem($item, $legacyLinkClassName)) {
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

            if (!$item->key instanceof Expr) {
                continue;
            }

            $memberName = $this->valueResolver->getValue($item->key);

            if (!is_string($memberName)) {
                continue;
            }

            // Add this member to the owns configuration
            if ($shouldAddMemberToOwns) {
                $this->addMemberToOwns($class, $memberName);
            }

            $hasChanged = true;
        }

        return $class;
    }

    /**
     * Refactor `$many_many` relation by:
     *
     * 1. Moving instances of `$legacyLinkClassName` to `$has_many`.
     * 2. Remove instance from `$many_many_extraFields`.
     * 3. Remove `$many_many` property if it is empty.
     */
    public function refactorManyMany(Class_ $class, Property $property, string $legacyLinkClassName, bool &$hasChanged): Class_
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
            if ($this->shouldSkipArrayItem($item, $legacyLinkClassName)) {
                continue;
            }

            // Add this member to the has_many property
            $this->addMemberToHasMany($class, $item);

            // Remove this member from the many_many_extraFields property
            $this->removeMemberFromManyManyExtraFields($class, $item);

            // Remove this member from the property
            unset($value->items[$key]);

            $hasChanged = true;
        }

        // If many_many is empty, remove it
        if ($value->items === []) {
            $this->removeProperty($class, $this->nodeNameResolver->getName($property));
        }

        return $class;
    }

    /**
     * Refactor `$has_many` relation by renaming instances of `$legacyLinkClassName` to `SilverStripe\LinkField\Models\Link.Owner`.
     */
    public function refactorHasMany(Class_ $class, Property $property, string $legacyLinkClassName, bool $shouldAddMemberToOwns, bool &$hasChanged): Class_
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

            if ($this->shouldSkipArrayItem($item, $legacyLinkClassName)) {
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

            if (!$item->key instanceof Expr) {
                continue;
            }

            $memberName = $this->valueResolver->getValue($item->key);

            if (!is_string($memberName)) {
                continue;
            }

            // Add this member to the owns configuration
            if ($shouldAddMemberToOwns) {
                $this->addMemberToOwns($class, $memberName);
            }

            $hasChanged = true;
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
            $this->removeProperty($class, $this->nodeNameResolver->getName($manyManyExtraFields));
        }
    }

    /**
     * Skip if the array item does not reference the legacy linl class.
     */
    private function shouldSkipArrayItem(ArrayItem $arrayItem, string $legacyLinkClassName): bool
    {
        $value = $arrayItem->value;

        if ($value instanceof Concat) {
            $value = $value->left;
        }

        if ($value instanceof ClassConstFetch) {
            return !$this->nodeNameResolver->isName($value->class, $legacyLinkClassName);
        }

        return true;
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
    }

    private function removeProperty(Class_ $class, string $propertyName): void
    {
        foreach ($class->stmts as $key => $stmt) {
            if (!$stmt instanceof Property) {
                continue;
            }

            if (!$this->nodeNameResolver->isName($stmt, $propertyName)) {
                continue;
            }

            unset($class->stmts[$key]);
        }
    }
}
