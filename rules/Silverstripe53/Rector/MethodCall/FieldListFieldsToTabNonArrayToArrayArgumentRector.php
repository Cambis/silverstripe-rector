<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @deprecated since 0.8.0 use \Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector instead
 *
 * @changelog https://github.com/silverstripe/silverstripe-framework/pull/11236
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabNonArrayToArrayArgumentRector\FieldListFieldsToTabNonArrayToArrayArgumentRectorTest
 */
final class FieldListFieldsToTabNonArrayToArrayArgumentRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<SilverstripeConstants::*>
     */
    private const METHOD_NAMES = [
        SilverstripeConstants::METHOD_ADD_FIELDS_TO_TAB,
        SilverstripeConstants::METHOD_REMOVE_FIELDS_FROM_TAB,
    ];

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change the second argument of `FieldList::addFieldsToTab()` and `FieldList::removeFieldsFromTab()` into an array.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\SilverStripe\Forms\FieldList::create()
    ->addFieldsToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

\SilverStripe\Forms\FieldList::create()
    ->removeFieldsFromTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\Forms\FieldList::create()
    ->addFieldsToTab('Root.Main', [\SilverStripe\Forms\TextField::create('Field')]);

\SilverStripe\Forms\FieldList::create()
    ->removeFieldsFromTab('Root.Main', [\SilverStripe\Forms\TextField::create('Field')]);
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
        if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\Forms\FieldList'))) {
            return null;
        }

        if (!$this->nodeNameResolver->isNames($node->name, self::METHOD_NAMES)) {
            return null;
        }

        $arg = $node->args[1] ?? null;

        if (!$arg instanceof Arg) {
            return null;
        }

        $argValue = $arg->value;

        if ($this->getType($argValue)->isArray()->yes()) {
            return null;
        }

        /** @phpstan-ignore-next-line */
        $node->args[1]->value = $this->nodeFactory->createArray([$argValue]);

        return $node;
    }
}
