<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(SilverstripeSetList::WITH_RECTOR_SERVICES);
    $rectorConfig->rule(AddManyManyMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->singleton(ConfigurationPropertyTypeResolverInterface::class, ConfigurationPropertyTypeResolver::class);
};
