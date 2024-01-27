<?php

declare(strict_types=1);

namespace SilverstripeRector\Rector\Class_;

use PhpParser\Node\Stmt\Class_;
use SilverStripe\Core\Extension;

abstract class AbstractAddAnnotationsToExtensionRector extends AbstractAddAnnotationsRector
{
    protected function shouldSkipClass(Class_ $class): bool
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

        return !$classReflection->isSubclassOf(Extension::class);
    }
}
