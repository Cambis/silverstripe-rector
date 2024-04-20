<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\Fixture;

$foo = \SilverStripe\Assets\File::get_by_id(\SilverStripe\Assets\File::class, 1, false);

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\Fixture;

$foo = \SilverStripe\Assets\File::get()->byID(1);

?>
