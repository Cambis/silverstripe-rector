<?php

use Cambis\SilverstripeRector\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector;
use Cambis\SilverstripeRector\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;

return RectorConfig::configure()
    ->withRules([
        GorriecoeLinkToSilverstripeLinkRector::class,
        GorriecoeLinkFieldToSilverstripeLinkFieldRector::class,
    ])
    ->withConfiguredRule(
        RenamePropertyRector::class,
        [new RenameProperty('gorriecoe\Link\Models\Link', 'LinkURL', 'URL')]
    )
    ->withConfiguredRule(
        RenameMethodRector::class,
        [new MethodCallRename('gorriecoe\Link\Models\Link', 'getLinkURL', 'getURL')]
    )
    ->withConfiguredRule(
        RenameClassRector::class,
        [
            'gorriecoe\Link\Models\Link' => 'SilverStripe\LinkField\Models\Link',
            'gorriecoe\LinkField\LinkField' => 'SilverStripe\LinkField\Form\LinkField',
        ]
    );
