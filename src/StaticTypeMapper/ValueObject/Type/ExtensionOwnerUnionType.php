<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type;

use Override;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * Internal type to represent `\Silverstripe\Core\Extensible|\Silverstripe\Core\Extension`.
 *
 * @deprecated since 1.0.0
 */
final class ExtensionOwnerUnionType extends UnionType
{
    #[Override]
    public function traverse(callable $cb): Type
    {
        $types = [];
        $hasChanged = false;

        foreach ($this->getTypes() as $type) {
            $newType = $cb($type);

            if ($type !== $newType) {
                $hasChanged = true;
            }

            // If the new type isn't one of the internally accepted types, fallback
            if (!$this->isInternalTypeAcceptable($newType)) {
                return parent::traverse($cb);
            }

            $types[] = $newType;
        }

        if ($hasChanged) {
            // Return a regular UnionType, so the type does not resolve to NEVER
            return new UnionType($types);
        }

        return $this;
    }

    private function isInternalTypeAcceptable(Type $type): bool
    {
        foreach ($type->getObjectClassReflections() as $classReflection) {
            if ($classReflection->hasTraitUse('SilverStripe\Core\Extensible')) {
                return true;
            }

            if ($classReflection->isSubclassOf('SilverStripe\Core\Extension')) {
                return true;
            }
        }

        return false;
    }
}
