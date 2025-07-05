<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector;
use Rector\Config\RectorConfig;

// https://docs.silverstripe.org/en/6/changelogs/6.1.0/
return static function (RectorConfig $rectorConfig): void {
    // Add Silverstripe53 PHPStan patch
    $rectorConfig->phpstanConfigs([
        SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH,
        SilverstripeOption::PHPSTAN_FOR_RECTOR_SILVERSTRIPE_53_PATH,
    ]);

    $rectorConfig->import(__DIR__ . '/../config.php');

    // https://github.com/silverstripe/silverstripe-framework/issues/11767
    $rectorConfig->rules([
        DataObjectGetByIdCachedRector::class,
        DataObjectGetOneCachedRector::class,
    ]);
};
