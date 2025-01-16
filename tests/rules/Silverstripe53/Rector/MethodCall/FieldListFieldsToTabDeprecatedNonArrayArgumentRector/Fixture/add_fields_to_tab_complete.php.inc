<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector\Fixture;

$fields = [\SilverStripe\Forms\TextField::create('Field')];

\SilverStripe\Forms\FieldList::create()
    ->addFieldsToTab('Root.Main', $fields);
