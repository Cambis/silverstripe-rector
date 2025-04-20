<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    // https://github.com/silverstripe/silverstripe-framework/pull/11613
    $rectorConfig->rule(ControllerHasCurrToInstanceofRector::class);
};
