<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\Fixture;

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
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
                new \SilverStripe\Forms\GridField\GridField(
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

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
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
                new \SilverStripe\LinkField\Form\MultiLinkField('ManyManyLinks', 'Many many links')
            );
    }
}

?>
