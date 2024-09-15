<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe53\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SilverstripeSetList::SILVERSTRIPE_53, SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52]);

    $rectorConfig->singleton(
        ConfigurationPropertyTypeResolverInterface::class,
        ConfigurationPropertyTypeResolver::class
    );
};
