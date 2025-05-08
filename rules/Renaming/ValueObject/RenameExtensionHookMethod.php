<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\ValueObject;

use Rector\Validation\RectorAssert;

final class RenameExtensionHookMethod
{
    /**
     * @readonly
     */
    public string $extensibleClassName;
    /**
     * @readonly
     */
    public string $oldMethodName;
    /**
     * @readonly
     */
    public string $newMethodName;
    public function __construct(
        string $extensibleClassName,
        string $oldMethodName,
        string $newMethodName
    ) {
        /**
         * Name of the class that is being extended.
         */
        $this->extensibleClassName = $extensibleClassName;
        /**
         * Name of the original extension hook.
         */
        $this->oldMethodName = $oldMethodName;
        /**
         * Name of the new extension hook.
         */
        $this->newMethodName = $newMethodName;
        RectorAssert::className($extensibleClassName);
        RectorAssert::methodName($oldMethodName);
        RectorAssert::methodName($newMethodName);
    }
}
