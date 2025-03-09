<?php

namespace App\Model\Block;

use App\Model\Block;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\FieldList;

final class LinkBlock extends Block
{
    /**
     * @var array{types: string[], title_display: bool}
     */
    private const LINK_CONFIG = [
        'types' => ['URL', 'SiteTree'],
        'title_display' => false,
    ];

    private static string $table_name = 'LinkBlock';

    private static array $has_one = [
        'Link' => Link::class,
    ];

    public function getCMSFields(): FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['LinkID'])
            ->addFieldToTab(
                'Root.Main',
                LinkField::create('Link', 'Link', $this, self::LINK_CONFIG),
            );
    }
}
