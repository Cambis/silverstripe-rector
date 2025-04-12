<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldReturnMethodCall extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        return $this->extendValidationResult(true, $validator);
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\Fixture;

class FormFieldReturnMethodCall extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;
        
        $this->extend('updateValidationResult', $result, $validator);
        
        return $result;
    }
}

?>
