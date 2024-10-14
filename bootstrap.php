<?php

declare(strict_types=1);

use SilverStripe\Control\CLIRequestBuilder;
use SilverStripe\Core\DatabaselessKernel;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\Connect\NullDatabase;
use SilverStripe\ORM\DB;

// We won't include `SilverStripe\Dev\TestOnly` by default
if (!defined('SILVERSTRIPE_RECTOR_INCLUDE_TESTS')) {
    define('SILVERSTRIPE_RECTOR_INCLUDE_TESTS', false);
}

// We don't need access to the database
DB::set_conn(new NullDatabase());

// Ensure that the proper globals are set
$globalVars = Environment::getVariables();
$globalVars['_SERVER']['REQUEST_URI'] = '';
$globalVars = CLIRequestBuilder::cleanEnvironment($globalVars);
Environment::setVariables($globalVars);

// Mock a Silverstripe application in order to access the Configuration API
$kernel = new class(BASE_PATH) extends DatabaselessKernel {
    protected function getIncludeTests()
    {
        return SILVERSTRIPE_RECTOR_INCLUDE_TESTS;
    }
};

// Preemptively generate the class manifest, so we can check for the existence of Page and PageController
$classLoader = $kernel->getClassLoader();
$classManifest = $classLoader->getManifest();
$classManifest->init(SILVERSTRIPE_RECTOR_INCLUDE_TESTS, false);

// If Page does not exist, add it!
if (!array_key_exists('page', $classManifest->getClassNames())) {
    $classManifest->handleFile(
        __DIR__ . '/stubs',
        __DIR__ . '/stubs/Page.php',
        false
    );

    $classLoader->loadClass('Page');
}

// If PageController does not exist, add it!
if (!array_key_exists('pagecontroller', $classManifest->getClassNames())) {
    $classManifest->handleFile(
        __DIR__ . '/stubs',
        __DIR__ . '/stubs/PageController.php',
        false
    );

    $classLoader->loadClass('PageController');
}

try {
    $kernel->boot();
} catch (Throwable $e) {
    if (Injector::inst()->has('Psr\Log\LoggerInterface')) {
        Injector::inst()->get('Psr\Log\LoggerInterface')->info($e->getMessage());
    }
}
