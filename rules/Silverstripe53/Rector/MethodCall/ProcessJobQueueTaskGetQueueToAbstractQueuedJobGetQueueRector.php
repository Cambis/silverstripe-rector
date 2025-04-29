<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall;

use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/symbiote/silverstripe-queuedjobs/commit/b6c1c4ffe3f4a577bf98cfcac4a7fb8fba94c0c0
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRectorTest
 */
final class ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `ProcessJobQueueTask::getQueue()` to `AbstractQueuedJob::getQueue()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FooTask extends \Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask
{
    public function run($request): void
    {
       // ...
       $queue = $this->getQueue($request);
       // ...
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class FooTask extends \Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask
{
    public function run($request): void
    {
       // ...
       $queue = \Symbiote\QueuedJobs\Services\AbstractQueuedJob::getQueue($request->getVar('queue') ?? 'Queued');
       // ...
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->var, new ObjectType('Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask'))) {
            return null;
        }
        if (!$this->isName($node->name, 'getQueue')) {
            return null;
        }
        $arg = $node->getArgs()[0] ?? null;
        if (!$arg instanceof Arg) {
            return null;
        }
        if (!$this->isObjectType($arg->value, new ObjectType('SilverStripe\Control\HTTPRequest'))) {
            return null;
        }
        return $this->nodeFactory->createStaticCall(
            'Symbiote\QueuedJobs\Services\AbstractQueuedJob',
            'getQueue',
            [
                new Coalesce(
                    $this->nodeFactory->createMethodCall($arg->value, 'getVar', ['queue']),
                    new String_('Queued')
                ),
            ]
        );
    }
}
