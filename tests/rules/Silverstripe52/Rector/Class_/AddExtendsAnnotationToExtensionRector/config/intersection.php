<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Cambis\SilverstripeRector\Silverstripe52\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeSetList::WITH_SILVERSTRIPE_API,
        SilverstripeSetList::WITH_RECTOR_SERVICES,
    ]);

    $rectorConfig->ruleWithConfiguration(
        AddExtendsAnnotationToExtensionRector::class,
        [
            'set_type_style' => 'intersection',
        ]
    );

    $rectorConfig->singleton(ConfigurationPropertyTypeResolverInterface::class, ConfigurationPropertyTypeResolver::class);
};
