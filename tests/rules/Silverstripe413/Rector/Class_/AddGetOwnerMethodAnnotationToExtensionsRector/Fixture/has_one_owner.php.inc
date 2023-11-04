<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector\Fixture;

class HasOneOwnerMock extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
------
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector\Fixture;

/**
 * @method \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector\Source\OwnerMock|$this getOwner()
 */
class HasOneOwnerMock extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
