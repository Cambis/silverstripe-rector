<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

/**
 * @see config/set/silverstripe52.php
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SilverstripeSetList::SS_5_0, SilverstripeSetList::SS_5_1, SilverstripeSetList::SS_5_2]);
};
