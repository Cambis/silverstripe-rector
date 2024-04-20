<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\PHPStan\Rector\Class_\AddConfigAnnotationToConfigurablePropertiesRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(AddConfigAnnotationToConfigurablePropertiesRector::class);
};
