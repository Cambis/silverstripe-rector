# Silverstripe Rector | Kaiako Ponga

This project contains [Rector rules](https://github.com/rectorphp/rector) for [Silverstripe CMS](https://github.com/silverstripe).

See the available [Silverstripe rules](docs/rector_rules_overview.md).

## Installation 👷‍♀️

Install via composer.

```sh
composer require --dev cambis/silverstripe-rector
```

### Recommended 💡
Add PSR-4 autoload setup in your `composer.json`. This will help Rector to discover your classes and give it a performance boost.

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

Verify everything is compliant.

```sh
composer dumpautoload -o
```

## Configuration 🚧

If you do not have an existing `rector.php` file, run the following command and Rector will create one for you.

```sh
vendor/bin/rector
```

Then use the `SilverstripeSetList` sets and pick one of the constants.

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
        // Automatically applies rules based on your composer.json
        SilverstripeSetList::COMPOSER_BASED,
        // Add magic method and property annotations to extensible objects
        SilverstripeSetList::TYPE_DECLARATIONS_DOCBLOCKS,
        // Help static analysis tools understand your code
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
