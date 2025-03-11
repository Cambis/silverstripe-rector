<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class ManyManyLinksStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $many_many = [
        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['ManyManyLinks'])
            ->addFieldToTab(
                'Root.Main',
                \SilverStripe\Forms\GridField\GridField::create(
                    'ManyManyLinks',
                    'Many many links',
                    $this->ManyManyLinks()
                )
            );
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class ManyManyLinksStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $many_many = [
        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['ManyManyLinks'])
            ->addFieldToTab(
                'Root.Main',
                \SilverStripe\LinkField\Form\MultiLinkField::create('ManyManyLinks', 'Many many links')
            );
    }
}

?>
