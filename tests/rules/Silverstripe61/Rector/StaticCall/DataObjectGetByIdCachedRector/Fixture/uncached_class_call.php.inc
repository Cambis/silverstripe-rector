<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$cached = false;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id(1, false);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id(1, $cached);

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$cached = false;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->byID(1);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache($cached)->byID(1);

?>
