<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

class HasOneExtensionMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

/**
 * @mixin \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\ExtensionMock
 */
class HasOneExtensionMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
