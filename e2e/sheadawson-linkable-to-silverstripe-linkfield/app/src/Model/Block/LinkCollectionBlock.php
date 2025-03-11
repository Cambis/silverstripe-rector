<?php

namespace App\Model\Block;

use App\Model\Block;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;

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
                GridField::create('Links', 'Links', $this->Links())
            );
    }
}
