<?php

namespace Symbiote\QueuedJobs\Tasks;

use function class_exists;

if (class_exists('Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask')) {
    return;
}

class ProcessJobQueueTask
{
}
