<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

// Include `SilverStripe\Dev\TestOnly` by default
if (!defined('SILVERSTRIPE_RECTOR_INCLUDE_TESTS')) {
    define('SILVERSTRIPE_RECTOR_INCLUDE_TESTS', true);
}
