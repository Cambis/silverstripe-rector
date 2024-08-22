<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property ?string $NotRequiredField
 * @property ?string $RequiredField
 */
class DBRequiredFields extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    public function getCMSCompositeValidator(): \SilverStripe\Forms\CompositeValidator
    {
        return parent::getCMSCompositeValidator()
            ->addValidator(new \SilverStripe\Forms\RequiredFields(
                ['RequiredField']
            ));
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property ?string $NotRequiredField
 * @property string $RequiredField
 */
class DBRequiredFields extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    public function getCMSCompositeValidator(): \SilverStripe\Forms\CompositeValidator
    {
        return parent::getCMSCompositeValidator()
            ->addValidator(new \SilverStripe\Forms\RequiredFields(
                ['RequiredField']
            ));
    }
}

?>
