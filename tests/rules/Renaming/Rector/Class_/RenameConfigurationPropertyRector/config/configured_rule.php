<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameConfigurationPropertyRector;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameConfigurationProperty;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(
        RenameConfigurationPropertyRector::class,
        [
            new RenameConfigurationProperty('SilverStripe\ORM\DataObject', 'description', 'class_description'),
        ]
    );
