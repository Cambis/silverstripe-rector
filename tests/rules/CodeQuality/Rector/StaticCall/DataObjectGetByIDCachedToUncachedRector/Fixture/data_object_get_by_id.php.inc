<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\Fixture;

$foo = \SilverStripe\Assets\File::get_by_id(1);

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\Fixture;

$foo = \SilverStripe\Assets\File::get()->byID(1);

?>
