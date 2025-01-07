<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeSetList::SILVERSTRIPE_RECTOR_TESTS,
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52,
    ]);
};
