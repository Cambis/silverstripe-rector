<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SilverstripeSetList::SS_5_1, SilverstripeLevelSetList::UP_TO_SS_5_0]);
};
