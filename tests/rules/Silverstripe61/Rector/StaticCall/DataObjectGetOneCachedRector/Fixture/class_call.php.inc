<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get_one();

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->setUseCache(true)->first();

?>
