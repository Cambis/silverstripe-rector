<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

/**
 * @see config/set/silverstripe52.php
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SilverstripeSetList::SILVERSTRIPE_50, SilverstripeSetList::SILVERSTRIPE_51, SilverstripeSetList::SILVERSTRIPE_52]);
};
