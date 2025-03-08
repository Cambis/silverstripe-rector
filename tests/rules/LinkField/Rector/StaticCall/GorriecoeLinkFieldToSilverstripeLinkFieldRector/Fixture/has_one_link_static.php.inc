<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLinkStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'CtaLink' => \gorriecoe\Link\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinkID'])
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

class HasOneLinkStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'CtaLink' => \gorriecoe\Link\Models\Link::class,
    ];

    public function getCMSFields(): \SilverStripe\Forms\FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['CtaLinkID'])
            ->addFieldToTab(
                'Root.Main',
                \SilverStripe\LinkField\Form\LinkField::create('CtaLinks', 'Links')->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class])->setExcludeLinkTextField(true)
            );
    }
}

?>
