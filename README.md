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

Ensure you have PSR-4 autoload setup in your `composer.json`.

```json
{
    "autoload": {
        "classmap": [
            "app/src/Page.php",
            "app/src/PageController.php"
        ],
        "psr-4": {
            "MyProjectNamespace\\": "app/src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "MyProjectNamespace\\Tests\\": "app/tests"
        }
    }
}
```

Verify everything is PSR-4 compliant.

```sh
composer dumpautoload -o
```

Rebuild your application.

```sh
vendor/bin/sake dev/build "flush=1"
```

## Configuration 🚧

If you do not have an existing `rector.php` file, run the following command and Rector will create one for you.

```sh
vendor/bin/rector
```

Then use the `SilverstripeLevelSetList` and `SilverstripeSetList` sets and pick one of the constants.

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

## Usage 🏃

Analyse your code with Rector and review the suggested changes.

```sh
vendor/bin/rector process --dry-run
```

Apply the suggested changes after they have been reviewed.

```sh
vendor/bin/rector process
```

For more information on usage, please refer to the [official docs](https://getrector.com/documentation).

## Troubleshooting 😢

You may run into some issues while running rector. If you do, hopefully you will find one of the following examples useful.

### Issue with the Rector autoloader

If you run into an issue such as 'Class ... was not found while trying to analyse it...', see the [official docs](https://getrector.com/documentation/static-reflection-and-autoload).

### Issue with the Silverstripe autoloader

If you receive an error such as 'System error: "Interface App\Contract\FooInterface was not found"', try rebuilding your application first before running Rector again.

```sh
vendor/bin/sake dev/build "flush=1"
```

If the problem still persists, check if you have imported the affected class incorrectly somewhere in your code. The following example illustrates this case. 

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

Fix the import casing and rebuild your application before running Rector again.

If the problem continues to persist you can attempt resolve it by including the affected file during the bootstrapping process.

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

// Feel free to leave the rest of file unchanged
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

Remember to rebuild your application first before running Rector again.
