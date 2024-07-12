<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Contract\Rector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Contract\Rector\RectorInterface;

interface APIAwareRectorInterface extends RectorInterface
{
    /**
     * Process Node of matched type with access to the Silverstripe Configuration and Injector APIs.
     *
     * @return Node|Node[]|null|NodeTraverser::*
     */
    public function refactorAPIAwareNode(Node $node);
}
