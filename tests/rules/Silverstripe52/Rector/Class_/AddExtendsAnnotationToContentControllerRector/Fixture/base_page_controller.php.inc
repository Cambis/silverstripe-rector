<?php

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Fixture;

class BasePageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Fixture;

/**
 * @template T of \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\Source\BasePage
 * @extends \SilverStripe\CMS\Controllers\ContentController<T>
 */
class BasePageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

?>
