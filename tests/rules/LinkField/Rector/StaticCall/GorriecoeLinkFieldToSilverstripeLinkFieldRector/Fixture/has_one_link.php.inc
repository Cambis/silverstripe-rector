<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
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
                new \gorriecoe\LinkField\LinkField('CtaLinks', 'Links', $this, ['types' => ['SiteTree'], 'title_display' => true])
            );
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
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
                (new \SilverStripe\LinkField\Form\LinkField('CtaLinks', 'Links'))->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class])->setExcludeLinkTextField(true)
            );
    }
}

?>
