<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall;

use Cambis\SilverstripeRector\Silverstripe61\NodeAnalyser\DataObjectArgsAnalyser;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/issues/11767
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\DataObjectGetOneCachedRectorTest
 */
final class DataObjectGetOneCachedRector extends AbstractRector implements DocumentedRuleInterface
{
    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly DataObjectArgsAnalyser $dataObjectArgsAnalyser,
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `DataObject::get_one()` to `DataObject::get()->setUseCache()->first()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
Foo::get_one();

\SilverStripe\ORM\DataObject::get_one(Foo::class);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
Foo::get()->setUseCache(true)->first();

Foo::get()->setUseCache(true)->first();
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
        if ($node->isFirstClassCallable()) {
            return null;
        }

        // Not supporting named arguments
        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        if (!$this->isObjectType($node->class, new ObjectType('SilverStripe\ORM\DataObject'))) {
            return null;
        }

        if (!$this->isName($node->name, 'get_one')) {
            return null;
        }

        $dataClass = $this->dataObjectArgsAnalyser->getDataClassNameFromArgs($node->getArgs()) ?? $this->getName($node->class);

        if ($dataClass === null || $dataClass === '') {
            return null;
        }

        // Call to `DataObject::get()`
        $dataListCall = $this->nodeFactory->createStaticCall($dataClass, 'get');

        $isCachedArg = $this->dataObjectArgsAnalyser->getIsCachedFromArgs($node->getArgs());
        $isCached = false;

        if (!$isCachedArg instanceof Arg) {
            $isCached = true;
        } elseif ($isCachedArg->value instanceof Variable) {
            $isCached = $isCachedArg;
        } else {
            $isCached = $this->valueResolver->getValue($isCachedArg);
        }

        // Add the call to `DataList::setUseCache()`, skip if the original argument was a constant `false`
        if ($isCached !== false) {
            $dataListCall = $this->nodeFactory->createMethodCall($dataListCall, 'setUseCache', [$isCached]);
        }

        return $this->nodeFactory->createMethodCall($dataListCall, 'first', []);
    }
}
