<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldMethodCall extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;

        $this->extendValidationResult($result, $validator);

        return $result;
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldMethodCall extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;

        $this->extend('updateValidationResult', $result, $validator);

        return $result;
    }
}

?>
