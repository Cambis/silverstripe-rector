<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector;
use Cambis\SilverstripeRector\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withSets([__DIR__ . '/../../config/config.php'])
    ->withRules([
        SheadawsonLinkableToSilverstripeLinkRector::class,
        SheadawsonLinkableFieldToSilverstripeLinkFieldRector::class,
    ])
    ->withConfiguredRule(
        RenameClassRector::class,
        [
            'Sheadawson\Linkable\Models\Link' => 'SilverStripe\LinkField\Models\Link',
            'Sheadawson\Linkable\Forms\LinkField' => 'SilverStripe\LinkField\Form\LinkField',
        ]
    );
