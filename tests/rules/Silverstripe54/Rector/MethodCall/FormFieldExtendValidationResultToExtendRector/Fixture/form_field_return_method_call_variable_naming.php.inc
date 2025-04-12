<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldReturnMethodCallVariableNaming extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;

        return $this->extendValidationResult(true, $validator);
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldReturnMethodCallVariableNaming extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;
        $result2 = true;

        $this->extend('updateValidationResult', $result2, $validator);

        return $result2;
    }
}

?>
