<?php

namespace App\Model\Block;

use App\Model\Block;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\FieldList;

final class LinkCollectionBlock extends Block
{
    private static string $table_name = 'LinkCollectionBlock';

    private static array $many_many = [
        'Links' => Link::class,
    ];

    private static array $many_many_extraFields = [
        'Links' => [
            'Sort' => 'Int',
        ],
    ];

    public function getCMSFields(): FieldList
    {
        return parent::getCMSFields()
            ->removeByName(['Links'])
            ->addFieldToTab(
                'Root.Main',
                LinkField::create('Links', 'Links', $this, [
                    'types' => ['URL', 'SiteTree'],
                ]),
            );
    }
}
