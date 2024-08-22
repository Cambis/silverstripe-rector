<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property ?string $NotRequiredField
 * @property ?string $RequiredField
 */
class DBRequiredFieldsExtended extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property ?string $NotRequiredField
 * @property string $RequiredField
 */
class DBRequiredFieldsExtended extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
