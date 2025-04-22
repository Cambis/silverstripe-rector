<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class RenameExtensionHookMethod
{
    public function __construct(
        /**
         * Name of the class that is being extended.
         */
        public string $extensibleClassName,
        /**
         * Name of the original extension hook.
         */
        public string $oldMethodName,
        /**
         * Name of the new extension hook.
         */
        public string $newMethodName,
    ) {
        RectorAssert::className($extensibleClassName);
        RectorAssert::methodName($oldMethodName);
        RectorAssert::methodName($newMethodName);
    }
}
