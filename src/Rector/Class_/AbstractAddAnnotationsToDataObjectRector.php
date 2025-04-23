<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Override;
use PhpParser\Node\Stmt\Class_;

abstract class AbstractAddAnnotationsToDataObjectRector extends AbstractAddAnnotationsRector
{
    #[Override]
    final protected function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isAnonymous()) {
            return true;
        }

        return !$this->classAnalyser->isDataObject($class);
    }
}
