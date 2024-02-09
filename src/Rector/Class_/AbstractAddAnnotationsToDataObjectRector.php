<?php

declare(strict_types=1);

namespace SilverstripeRector\Rector\Class_;

use Override;
use PhpParser\Node\Stmt\Class_;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataObject;

abstract class AbstractAddAnnotationsToDataObjectRector extends AbstractAddAnnotationsRector
{
    #[Override]
    final protected function shouldSkipClass(Class_ $class): bool
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

        if ($classReflection->isSubclassOf(Extension::class)) {
            return false;
        }

        return !$classReflection->isSubclassOf(DataObject::class);
    }
}
