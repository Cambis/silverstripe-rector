<?php

use Cambis\SilverstripeRector\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector;
use Cambis\SilverstripeRector\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withRules([
        GorriecoeLinkToSilverstripeLinkRector::class,
        GorriecoeLinkFieldToSilverstripeLinkFieldRector::class,
    ])
    ->withConfiguredRule(
        RenameClassRector::class,
        [
            'gorriecoe\Link\Models\Link' => 'SilverStripe\LinkField\Models\Link',
            'gorriecoe\LinkField\LinkField' => 'SilverStripe\LinkField\Form\LinkField',
        ]
    );
