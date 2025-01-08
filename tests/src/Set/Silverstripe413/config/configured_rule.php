<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeSetList::WITH_RECTOR_SERVICES,
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_413,
    ]);
};
