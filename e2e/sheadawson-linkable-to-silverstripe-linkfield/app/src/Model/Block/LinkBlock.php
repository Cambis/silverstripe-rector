<?php

namespace App\Model\Block;

use App\Model\Block;
use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Forms\FieldList;

final class LinkBlock extends Block
{
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
                LinkField::create('LinkID', 'Link'),
            );
    }
}
