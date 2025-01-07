<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector;

use Cambis\SilverstripeRector\Contract\Rector\APIAwareRectorInterface;
use Override;
use PhpParser\Node;
use Rector\Rector\AbstractRector;

/**
 * A rector rule that has access to the Silverstripe Injector and Configuration APIs.
 *
 * @deprecated since 1.0.0
 */
abstract class AbstractAPIAwareRector extends AbstractRector implements APIAwareRectorInterface
{
    #[Override]
    final public function refactor(Node $node)
    {
        return $this->refactorAPIAwareNode($node);
    }
}
