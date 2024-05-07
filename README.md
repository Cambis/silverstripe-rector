# Silverstripe Rector | Kaiako Ponga

This project contains [Rector rules](https://github.com/rectorphp/rector) for [Silverstripe CMS](https://github.com/silverstripe).

See available [Silverstripe rules](docs/rector_rules_overview.md).

## Prerequisites 🦺

```sh
php ^7.4 || ^8.0
silverstripe/framework ^4.0 || ^5.0
silverstripe/cms ^4.0 || ^5.0
```

## Installation 👷‍♀️

Install via composer.

```sh
composer require --dev cambis/silverstripe-rector
```

## Configuration 🚧

Use the `SilverstripeLevelSetList` and `SilverstripeSetList` sets and pick one of the constants.

```php
<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app/_config.php',
        __DIR__ . '/app/src',
        __DIR__ . '/app/tests',
    ])
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52,
        SilverstripeSetList::CODE_QUALITY,
    ]);
```

## Troubleshooting 😢

You may run into some issues while running rector. If you do, hopefully you will find one of the following examples useful.

### Issue with the Rector autoloader

If you run into an issue such as 'Class ... was not found while trying to analyse it...', see the [official docs](https://getrector.com/documentation/static-reflection-and-autoload).

### Issue with the Silverstripe autoloader

If you receive an error such as 'System error: "Interface App\Contract\FooInterface was not found", the common cause if that you have imported the affected class incorrectly somewhere in your code. The following example illustrates this case. 

```php
<?php

namespace App\Contract;

interface FooInterface
{
}

namespace App\Model;

use App\Contract\Foointerface; // <--- The casing for this use statement is wrong and will likely cause an error.
use SilverStripe\ORM\DataObject;

class Foo extends DataObject implements Foointerface
{
}
```

If the problem persists you can attempt resolve it by including the affected file during the bootstrapping process.

First copy the existing bootstrap file:

```sh
cp vendor/cambis/silverstripe-rector/bootstrap.php ./rector-bootstrap.php
```

Then modifiy the file as so:

```diff
<?php

declare(strict_types=1);

+use App\Contract\FooInterface
use SilverStripe\Core\DatabaselessKernel;
use SilverStripe\ORM\Connect\NullDatabase;
use SilverStripe\ORM\DB;

+// Include any 'missing' files here using the following format:
+if (!class_exists(FooInterface::class)) {
+    require_once __DIR__ . '/app/src/Contract/FooInterface.php';
+}

-// Add Page/PageController stubs which may be required
+// Add Page/PageController
if (!class_exists(Page::class)) {
-    require __DIR__ . '/stubs/Page.php';
+    require_once __DIR__ . '/app/src/Page.php';
}

if (!class_exists(PageController::class)) {
-    require __DIR__ . '/stubs/PageController.php';
+    require_once __DIR__ . '/app/src/PageController.php';
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
```

Finally, include the custom bootstrap file in your configuration:

```php
<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withBootstrapFiles([
        // Include the custom bootstrap file here
        __DIR__ . '/rector-bootstrap.php',
    ])
    ->withPaths([
        __DIR__ . '/app/_config.php',
        __DIR__ . '/app/src',
        __DIR__ . '/app/tests',
    ])
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52,
    ]);
```
