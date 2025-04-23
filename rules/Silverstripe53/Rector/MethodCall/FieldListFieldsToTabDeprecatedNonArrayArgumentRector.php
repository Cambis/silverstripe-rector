<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_keys;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/pull/11236
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector\FieldListFieldsToTabDeprecatedNonArrayArgumentRectorTest
 */
final class FieldListFieldsToTabDeprecatedNonArrayArgumentRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<SilverstripeConstants::*, SilverstripeConstants::*>
     */
    private const METHOD_NAMES = [
        'addFieldsToTab' => 'addFieldToTab',
        'removeFieldsFromTab' => 'removeFieldFromTab',
    ];

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename `FieldList::addFieldsToTab()` and `FieldList::removeFieldsFromTab()` to `FieldList::addFieldToTab()` and `FieldList::removeFieldFromTab()` respectively if the second argument is not an array.', [
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
    ->addFieldToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

\SilverStripe\Forms\FieldList::create()
    ->removeFieldFromTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));
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

        if (!$this->nodeNameResolver->isNames($node->name, array_keys(self::METHOD_NAMES))) {
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

        foreach (self::METHOD_NAMES as $originalName => $newName) {
            if (!$this->nodeNameResolver->isName($node->name, $originalName)) {
                continue;
            }

            $node->name = new Identifier($newName);

            break;
        }

        return $node;
    }
}
