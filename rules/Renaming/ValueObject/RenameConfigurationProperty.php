<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class RenameConfigurationProperty
{
    public function __construct(
        public string $extensibleClassName,
        public string $oldPropertyName,
        public string $newPropertyName
    ) {
        RectorAssert::className($extensibleClassName);
        RectorAssert::propertyName($oldPropertyName);
        RectorAssert::propertyName($newPropertyName);
    }
}
