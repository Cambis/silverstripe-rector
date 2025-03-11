<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector::class]);
