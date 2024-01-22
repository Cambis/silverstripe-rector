<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeLevelSetList::UP_TO_SS_5_2,
    ]);
};
