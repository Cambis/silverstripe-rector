<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class SkipGridField extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        $this->beforeUpdateCMSFields(function (\SilverStripe\Forms\FieldList $fields): void {
            $fields->addFieldToTab(
                'Root.Main',
                \SilverStripe\Forms\GridField\GridField::create(
                    'SomeRelation',
                    'SomeRelation',
                    $this->SomeRelation()
                )
            );
        });

        return parent::getCMSFields();
    }
}
