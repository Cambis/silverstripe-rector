<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector\Fixture;

\SilverStripe\View\SSViewer::get_base_tag('some content');

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector\Fixture;

\SilverStripe\View\SSViewer::getBaseTag(preg_match('/<!DOCTYPE[^>]+xhtml/i', 'some content') === 1);

?>
