# Silverstripe Rector | Kaiako Ponga

This project contains [Rector rules](https://github.com/rectorphp/rector) for [Silverstripe CMS](https://github.com/silverstripe).

See available [Silverstripe rules](docs/rector_rules_overview.md).

## Prerequisites 🦺

```sh
silverstripe/framework ^4.0 || ^5.0
silverstripe/cms ^4.0 || ^5.0
```

## Installation 👷‍♀️

Install via composer.

```sh
composer require --dev rector/rector
composer require --dev cambis/silverstripe-rector
```

## Configuration 🚧

Use the `SilverstripeLevelSetList` and `SilverstripeSetList` sets and pick one of the constants.

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeLevelSetList::UP_TO_SS_5_2,
        SilverstripeSetList::CODE_QUALITY,
    ]);
};
```
