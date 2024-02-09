<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SilverstripeSetList::SILVERSTRIPE_51, SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_50]);
};
