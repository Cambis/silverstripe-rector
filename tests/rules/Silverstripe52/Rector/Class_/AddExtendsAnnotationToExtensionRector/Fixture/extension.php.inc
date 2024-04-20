<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

class Extension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<static>
 */
class Extension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
