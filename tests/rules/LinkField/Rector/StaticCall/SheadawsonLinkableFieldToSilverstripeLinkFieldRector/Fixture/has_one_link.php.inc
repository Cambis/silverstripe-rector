<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['HasOneLinkID'])
            ->addFieldToTab(
                'Root.Main',
                new \Sheadawson\Linkable\Forms\LinkField('HasOneLinkID', 'Has one link')
            );
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['HasOneLinkID'])
            ->addFieldToTab(
                'Root.Main',
                new \SilverStripe\LinkField\Form\LinkField('HasOneLink', 'Has one link')
            );
    }
}

?>
