<?php

namespace {

    use App\Model\Block;
    use SilverStripe\CMS\Model\SiteTree;

    class Page extends SiteTree
    {
        private static array $has_many = [
            'Blocks' => Block::class,
        ];
    }
}
