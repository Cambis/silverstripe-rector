<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector\Fixture;

\SilverStripe\Forms\FieldList::create()
    ->addFieldsToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector\Fixture;

\SilverStripe\Forms\FieldList::create()
    ->addFieldToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

?>
