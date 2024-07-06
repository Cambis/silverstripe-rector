<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use Cambis\SilverstripeRector\Silverstripe413\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(AddExtensionMixinAnnotationsToExtensibleRector::class);
    $rectorConfig->singleton(ConfigurationPropertyTypeResolverInterface::class, ConfigurationPropertyTypeResolver::class);
};
