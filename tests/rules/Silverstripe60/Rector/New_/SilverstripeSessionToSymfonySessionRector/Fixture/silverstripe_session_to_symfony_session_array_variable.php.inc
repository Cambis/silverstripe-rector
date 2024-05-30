<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\New_\SilverstripeSessionToSymfonySessionRector\Fixture;

final class Foo implements \SilverStripe\Dev\TestOnly
{
    private $session;

    public function __construct()
    {
        $config = ['something' => ['some' => 'value', 'another' => 'item']];

        $this->session = new \SilverStripe\Control\Session($config);
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\New_\SilverstripeSessionToSymfonySessionRector\Fixture;

final class Foo implements \SilverStripe\Dev\TestOnly
{
    private $session;

    public function __construct()
    {
        $config = ['something' => ['some' => 'value', 'another' => 'item']];

        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();

        foreach ($config as $sessionKey => $sessionValue) {
            $this->session->set($sessionKey, $sessionValue);
        }
    }
}

?>
