<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

class HasOneExtensionReplaced extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

/**
 * @mixin \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\ExtensionMock
 */
class HasOneExtensionReplaced extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
