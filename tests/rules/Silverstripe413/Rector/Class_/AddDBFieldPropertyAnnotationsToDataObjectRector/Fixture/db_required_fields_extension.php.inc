<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property ?string $NotRequiredField
 * @property ?string $RequiredField
 */
class DBRequiredFieldsExtension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateCMSCompositeValidator(\SilverStripe\Forms\CompositeValidator $compositeValidator): void
    {
        $compositeValidator->addValidator(new \SilverStripe\Forms\RequiredFields(
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
class DBRequiredFieldsExtension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateCMSCompositeValidator(\SilverStripe\Forms\CompositeValidator $compositeValidator): void
    {
        $compositeValidator->addValidator(new \SilverStripe\Forms\RequiredFields(
            ['RequiredField']
        ));
    }
}

?>
