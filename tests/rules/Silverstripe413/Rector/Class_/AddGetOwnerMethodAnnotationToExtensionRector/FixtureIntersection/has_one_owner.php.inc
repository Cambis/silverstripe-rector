<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\FixtureIntersection;

class HasOneOwner extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\FixtureIntersection;

/**
 * @method (\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockOne & static) getOwner()
 */
class HasOneOwner extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
