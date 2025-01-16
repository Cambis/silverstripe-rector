<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FieldListFieldsToTabDeprecatedNonArrayArgumentRector::class]);
