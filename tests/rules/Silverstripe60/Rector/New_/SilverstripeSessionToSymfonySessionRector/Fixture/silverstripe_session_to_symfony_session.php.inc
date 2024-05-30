<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\Class_\SilverstripeSessionToSymfonySessionRector\Fixture;

final class Foo implements \SilverStripe\Dev\TestOnly
{
    private $session;

    public function __construct()
    {
        $this->session = new \SilverStripe\Control\Session(null);
    }
}
?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\Class_\SilverstripeSessionToSymfonySessionRector\Fixture;

final class Foo implements \SilverStripe\Dev\TestOnly
{
    private $session;

    public function __construct()
    {
        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();
    }
}
?>
