<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\New_\SilverstripeSessionToSymfonySessionRector\Fixture;

final class Foo implements \SilverStripe\Dev\TestOnly
{
    private $session;

    public function __construct()
    {
        $this->session = new \SilverStripe\Control\Session(['something' => ['some' => 'value', 'another' => 'item']]);
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
        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();
        
        foreach (['something' => ['some' => 'value', 'another' => 'item']] as $sessionKey => $sessionValue) {
            $this->session->set($sessionKey, $sessionValue);
        }
    }
}

?>
