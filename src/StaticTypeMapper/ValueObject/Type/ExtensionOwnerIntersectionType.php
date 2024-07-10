<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Extension;

/**
 * Internal type to represent `\Silverstripe\Core\Extensible&\Silverstripe\Core\Extension`.
 */
final class ExtensionOwnerIntersectionType extends IntersectionType
{
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
        if (!$type instanceof StaticType && !$type instanceof ObjectType) {
            return false;
        }

        $classReflection = $type->getClassReflection();

        if (!$classReflection instanceof ClassReflection) {
            return false;
        }

        if ($classReflection->hasTraitUse(Extensible::class)) {
            return true;
        }

        return $classReflection->isSubclassOf(Extension::class);
    }
}
