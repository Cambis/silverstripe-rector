<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\Fixture;

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $many_many = [
        'CtaLinks' => \gorriecoe\Link\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinks'])
            ->addFieldToTab(
                'Root.Main',
                \gorriecoe\LinkField\LinkField::create('CtaLinks', 'Links', $this, ['types' => ['SiteTree'], 'title_display' => true])
            );
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\Fixture;

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $many_many = [
        'CtaLinks' => \gorriecoe\Link\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinks'])
            ->addFieldToTab(
                'Root.Main',
                \SilverStripe\LinkField\Form\MultiLinkField::create('CtaLinks', 'Links')->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class])->setExcludeLinkTextField(true)
            );
    }
}

?>
