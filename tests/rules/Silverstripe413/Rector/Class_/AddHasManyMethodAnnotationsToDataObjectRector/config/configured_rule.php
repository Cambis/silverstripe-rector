<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddHasManyMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->singleton(ConfigurationPropertyTypeResolverInterface::class, ConfigurationPropertyTypeResolver::class);
};
