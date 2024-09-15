<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabNonArrayToArrayArgumentRector\Fixture;

\SilverStripe\Forms\FieldList::create()
    ->removeFieldsFromTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabNonArrayToArrayArgumentRector\Fixture;

\SilverStripe\Forms\FieldList::create()
    ->removeFieldsFromTab('Root.Main', [\SilverStripe\Forms\TextField::create('Field')]);

?>