<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/pull/11613
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector\ControllerHasCurrToInstanceofRectorTest
 */
final class ControllerHasCurrToInstanceofRector extends AbstractRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `Controller::has_curr()` check to `Controller::curr() instanceof Controller`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
if (\SilverStripe\Control\Controller::has_curr()) {
   // ...
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
if (\SilverStripe\Control\Controller::curr() instanceof \SilverStripe\Control\Controller) {
   // ...
}
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
        if (!$this->isObjectType($node->class, new ObjectType('SilverStripe\Control\Controller'))) {
            return null;
        }

        if (!$this->isName($node->name, 'has_curr')) {
            return null;
        }

        $staticCall = $this->nodeFactory->createStaticCall($this->getName($node->class) ?? 'SilverStripe\Control\Controller', 'curr');

        return $this->createExprInstanceof($staticCall, new ObjectType('SilverStripe\Control\Controller'));
    }

    private function createExprInstanceof(Expr $expr, ObjectType $objectType): Instanceof_
    {
        $fullyQualified = new FullyQualified($objectType->getClassName());

        return new Instanceof_($expr, $fullyQualified);
    }
}
