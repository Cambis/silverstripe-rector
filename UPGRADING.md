# Upgrading

## From 2.1 to 2.2

### New composer based set

We have introduced a new composer based set based on rector's new rule discovery pattern, `SilverstripeSetList::COMPOSER_BASED`. This set will apply rector rules based on the constraints inside your composer.json file. See how this pattern works [here](http://getrector.com/documentation/composer-based-sets#content-how-it-works).

As a result we have deprecated all of the pre-existing `SilverstripeSetList::SILVERSTRIPE_XXX` and `SilverstripeLevelSetList` sets, these will be removed in a later major version.

Replace any calls to the deprecated setlists with the new composer based one, the change could be as simple as the diff below.

```diff
<?php

declare(strict_types=1);

-use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app/_config.php',
        __DIR__ . '/app/src',
        __DIR__ . '/app/tests',
    ])
    ->withSets([
-        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52,
+        SilverstripeSetList::COMPOSER_BASED,
    ]);
```

### Magic method and property annotation rector rules are now opt-in

Previously rector rules that added magic method and property annotations were a part of the regular sets. This often at times presented a performance bottleneck in large codebases as these rector rules had to do some expensive logic in order to generate the required annotations. While it is still highly recommended that you add the annotations in order to help out static analysis tools, we will leave when they run up to you.

To opt-in to these rules, add the new `SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS` set to your rector config.
