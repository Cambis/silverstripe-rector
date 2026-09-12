<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\StaticCall;

use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function is_string;
use function rtrim;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/01_linkable-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\SheadawsonLinkableFieldToSilverstripeLinkFieldRectorTest
 */
final class SheadawsonLinkableFieldToSilverstripeLinkFieldRector extends AbstractLinkFieldRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `Sheadawson\Linkable\Forms\LinkField` to `SilverStripe\LinkField\Form\LinkField`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\Sheadawson\Linkable\Forms\LinkField::create('LinkID', 'Link');

\SilverStripe\Forms\GridField\GridField::create('Links', 'Links', $this->Links());
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\LinkField\Form\LinkField::create('Link', 'Link');

\SilverStripe\LinkField\Form\MultiLinkField::create('Links', 'Links');
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param New_|StaticCall $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($node instanceof StaticCall && !$this->isName($node->name, 'create')) {
            return null;
        }

        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        if ($this->isName($node->class, 'Sheadawson\Linkable\Forms\LinkField')) {
            return $this->refactorLinkField($node);
        }

        if ($this->isName($node->class, 'SilverStripe\Forms\GridField\GridField')) {
            return $this->refactorGridField($node);
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\StaticCall $node
     */
    private function refactorLinkField($node): ?Node
    {
        $fieldNameArg = $node->getArgs()[0] ?? null;
        $fieldTitleArg = $node->getArgs()[1] ?? null;

        // Safety checks...
        if (!$fieldNameArg instanceof Arg) {
            return null;
        }

        $fieldName = $this->valueResolver->getValue($fieldNameArg);

        // Safety check
        if (!is_string($fieldName)) {
            return null;
        }

        // Build the new args
        $args = [$this->nodeFactory->createArg(rtrim($fieldName, 'ID'))];

        // Add the title arg if it exists
        if ($fieldTitleArg instanceof Arg) {
            $args[] = $fieldTitleArg;
        }

        return $this->newFactory->createInjectable(
            'SilverStripe\LinkField\Form\LinkField',
            $args,
            $node instanceof StaticCall
        );
    }
}
