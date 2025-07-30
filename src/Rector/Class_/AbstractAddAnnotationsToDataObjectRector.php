<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use PhpParser\Node\Stmt\Class_;

abstract class AbstractAddAnnotationsToDataObjectRector extends AbstractAddAnnotationsRector
{
    final protected function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isAnonymous()) {
            return true;
        }
        return !$this->classAnalyser->isDataObject($class);
    }
}
