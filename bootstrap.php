<?php

declare(strict_types=1);

use SilverStripe\Core\DatabaselessKernel;
use SilverStripe\ORM\Connect\NullDatabase;
use SilverStripe\ORM\DB;

// Add Page/PageController stubs which may be required
if (!class_exists(Page::class)) {
    require __DIR__ . '/stubs/Page.php';
}

if (!class_exists(PageController::class)) {
    require __DIR__ . '/stubs/PageController.php';
}

DB::set_conn(new NullDatabase());

// Mock a Silverstripe application in order to access the Configuration API
try {
    $kernel = new class(BASE_PATH) extends DatabaselessKernel {
        protected function getIncludeTests()
        {
            return true;
        }
    };

    $kernel->boot();
} catch (Throwable $e) {
    echo $e->getMessage();
}
