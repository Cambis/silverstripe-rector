<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_filter;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector\ViewableDataCachedCallToObjRectorTest
 */
final class ViewableDataCachedCallToObjRector extends AbstractRector implements DocumentedRuleInterface
{
    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `ViewableData::cachedCall()` to `ViewableData::obj()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\SilverStripe\View\ViewableData::create()->cachedCall('Foo', [], null);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\View\ViewableData::create()->obj('Foo', [], true, null);
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\View\ViewableData'))) {
            return null;
        }

        if (!$this->isName($node->name, 'cachedCall')) {
            return null;
        }

        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $node->var,
            'obj',
            array_filter([
                $node->getArgs()[0] ?? '',
                $node->getArgs()[1] ?? [],
                true,
                $node->getArgs()[2] ?? null,
            ])
        );
    }
}
