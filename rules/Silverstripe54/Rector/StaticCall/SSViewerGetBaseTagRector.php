<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\LNumber;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector\SSViewerGetBaseTagRectorTest
 */
final class SSViewerGetBaseTagRector extends AbstractRector implements DocumentedRuleInterface
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `SSViewer::get_base_tag()` to `SSViewer::getBaseTag()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\SilverStripe\View\SSViewer::get_base_tag('some content');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\View\SSViewer::getBaseTag(preg_match('/<!DOCTYPE[^>]+xhtml/i', 'some content') === 1);
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->class, new ObjectType('SilverStripe\View\SSViewer'))) {
            return null;
        }

        if (!$this->isName($node->name, 'get_base_tag')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'SilverStripe\View\SSViewer',
            SilverstripeConstants::METHOD_GET_BASE_TAG,
            [
                new Identical(
                    $this->nodeFactory->createFuncCall('preg_match', ['/<!DOCTYPE[^>]+xhtml/i', $node->getArgs()[0] ?? '']),
                    new LNumber(1)
                ),
            ]
        );
    }
}
