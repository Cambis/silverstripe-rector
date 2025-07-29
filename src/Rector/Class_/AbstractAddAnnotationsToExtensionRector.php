<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use function in_array;

abstract class AbstractAddAnnotationsToExtensionRector extends AbstractAddAnnotationsRector
{
    final protected function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isAnonymous()) {
            return true;
        }
        if (!$this->classAnalyser->isExtension($class)) {
            return true;
        }
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $parentReflection = $classReflection->getParentClass();
        if (!$parentReflection instanceof ClassReflection) {
            return true;
        }
        // Only allow child of these classes, no subchilds allowed
        return !in_array($parentReflection->getName(), $this->getAllowedParents(), true);
    }

    /**
     * @return list<class-string>
     */
    final protected function getAllowedParents(): array
    {
        return ['SilverStripe\Core\Extension', 'SilverStripe\ORM\DataExtension'];
    }
}
