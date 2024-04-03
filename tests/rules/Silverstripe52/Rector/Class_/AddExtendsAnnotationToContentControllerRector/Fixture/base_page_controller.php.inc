<?php

namespace SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Fixture;

class BasePageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

?>
-----
<?php

namespace SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Fixture;

/**
 * @template T of \SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Source\BasePage
 * @extends \SilverStripe\CMS\Controllers\ContentController<T>
 */
class BasePageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

?>
