<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type;

use Override;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Type;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Extension;

/**
 * Internal type to represent `\Silverstripe\Core\Extensible&\Silverstripe\Core\Extension`.
 *
 * @deprecated since 1.0.0
 */
final class ExtensionOwnerIntersectionType extends IntersectionType
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

            // If the type isn't one of the interally accepted types, fallback
            if (!$this->isInternalTypeAcceptable($newType)) {
                return parent::traverse($cb);
            }

            $types[] = $newType;
        }

        if ($hasChanged) {
            // Return a regular IntersectionType, so the type doesn't resolve to NEVER
            return new IntersectionType($types);
        }

        return $this;
    }

    private function isInternalTypeAcceptable(Type $type): bool
    {
        foreach ($type->getObjectClassReflections() as $classReflection) {
            if ($classReflection->hasTraitUse(Extensible::class)) {
                return true;
            }

            if ($classReflection->isSubclassOf(Extension::class)) {
                return true;
            }
        }

        return false;
    }
}
