<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

class DataExtension extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

/**
 * @method (\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockOne & static) getOwner()
 */
class DataExtension extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
