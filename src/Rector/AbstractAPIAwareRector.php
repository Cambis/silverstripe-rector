<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector;

use Cambis\SilverstripeRector\Contract\Rector\APIAwareRectorInterface;
use Override;
use PhpParser\Node;
use Rector\Exception\ShouldNotHappenException;
use Rector\Rector\AbstractRector;
use SilverStripe\Core\Config\ConfigLoader;
use SilverStripe\Core\Injector\InjectorLoader;

abstract class AbstractAPIAwareRector extends AbstractRector implements APIAwareRectorInterface
{
    #[Override]
    final public function refactor(Node $node)
    {
        // Check that we have access.
        if (!$this->hasAPIAccess()) {
            throw new ShouldNotHappenException(
                'This rule requires access to the Silverstripe Injector and Configuration APIs. ' .
                'Include the `SilverstripeSetList::WITH_SILVERSTRIPE_API` set in your rector config.'
            );
        }

        return $this->refactorAPIAwareNode($node);
    }

    private function hasAPIAccess(): bool
    {
        return ConfigLoader::inst()->hasManifest() && InjectorLoader::inst()->hasManifest();
    }
}
