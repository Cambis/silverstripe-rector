<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector\Fixture;

class ProcessJobQueueTask extends \Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask
{
    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request): void
    {
        $this->getQueue($request);
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector\Fixture;

class ProcessJobQueueTask extends \Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask
{
    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request): void
    {
        \Symbiote\QueuedJobs\Services\AbstractQueuedJob::getQueue($request->getVar('queue') ?? 'Queued');
    }
}

?>
