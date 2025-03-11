<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLinkStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinkID'])
            ->addFieldToTab(
                'Root.Main',
                \Sheadawson\Linkable\Forms\LinkField::create('HasOneLinkID', 'Has one link')
            );
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLinkStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinkID'])
            ->addFieldToTab(
                'Root.Main',
                \SilverStripe\LinkField\Form\LinkField::create('HasOneLink', 'Has one link')
            );
    }
}

?>
