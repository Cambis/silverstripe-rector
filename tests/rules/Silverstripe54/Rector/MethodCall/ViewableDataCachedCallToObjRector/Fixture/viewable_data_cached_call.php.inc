<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector\Fixture;

\SilverStripe\View\ViewableData::create()->cachedCall('Foo', []);

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector\Fixture;

\SilverStripe\View\ViewableData::create()->obj('Foo', [], true);

?>
