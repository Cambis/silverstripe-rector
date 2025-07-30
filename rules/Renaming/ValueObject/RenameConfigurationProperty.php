<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\ValueObject;

use Rector\Validation\RectorAssert;

final class RenameConfigurationProperty
{
    /**
     * @readonly
     */
    public string $extensibleClassName;
    /**
     * @readonly
     */
    public string $oldPropertyName;
    /**
     * @readonly
     */
    public string $newPropertyName;
    public function __construct(
        string $extensibleClassName,
        string $oldPropertyName,
        string $newPropertyName
    ) {
        $this->extensibleClassName = $extensibleClassName;
        $this->oldPropertyName = $oldPropertyName;
        $this->newPropertyName = $newPropertyName;
        RectorAssert::className($extensibleClassName);
        RectorAssert::propertyName($oldPropertyName);
        RectorAssert::propertyName($newPropertyName);
    }
}
