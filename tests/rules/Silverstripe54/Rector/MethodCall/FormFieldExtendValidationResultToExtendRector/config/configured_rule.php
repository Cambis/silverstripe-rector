<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FormFieldExtendValidationResultToExtendRector::class]);
