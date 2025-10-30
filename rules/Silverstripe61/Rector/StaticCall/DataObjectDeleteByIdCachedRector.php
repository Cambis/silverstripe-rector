<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall;

use Cambis\SilverstripeRector\Silverstripe61\NodeAnalyser\DataObjectArgsAnalyser;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\DataObjectDeleteByIdCachedRectorTest
 */
final class DataObjectDeleteByIdCachedRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ArgsAnalyzer $argsAnalyzer;
    /**
     * @readonly
     */
    private DataObjectArgsAnalyser $dataObjectArgsAnalyser;
    public function __construct(ArgsAnalyzer $argsAnalyzer, DataObjectArgsAnalyser $dataObjectArgsAnalyser)
    {
        $this->argsAnalyzer = $argsAnalyzer;
        $this->dataObjectArgsAnalyser = $dataObjectArgsAnalyser;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `DataObject::delete_by_id()` to `DataObject::get()->setUseCache()->byID()->delete()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\SilverStripe\ORM\DataObject::delete_by_id(Foo::class, 1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
Foo::get()->setUseCache(true)->byID(1)?->delete();
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Not supporting named arguments
        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        // Can't do anything if no args were supplied
        if ($node->getArgs() === []) {
            return null;
        }

        if (!$this->isObjectType($node->class, new ObjectType('SilverStripe\ORM\DataObject'))) {
            return null;
        }

        if (!$this->isName($node->name, 'delete_by_id')) {
            return null;
        }

        $dataClass = $this->dataObjectArgsAnalyser->getDataClassNameFromArgs($node->getArgs()) ?? '';

        if ($dataClass === '') {
            return null;
        }

        $id = $this->dataObjectArgsAnalyser->getIdFromArgs($node->getArgs());

        // This shouldn't happen in reality but we'll fail silently just in case
        if (!$id instanceof Arg) {
            return null;
        }

        $dataListCall = $this->nodeFactory->createStaticCall($dataClass, 'get');
        $dataListCall = $this->nodeFactory->createMethodCall($dataListCall, 'setUseCache', [true]);
        $dataListCall = $this->nodeFactory->createMethodCall($dataListCall, 'byID', [$id]);

        return new NullsafeMethodCall($dataListCall, 'delete');
    }
}
