<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\StaticCall;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\Normaliser\Normaliser;
use Cambis\SilverstripeRector\NodeFactory\NewFactory;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;
use function is_array;
use function is_string;
use function rtrim;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/01_linkable-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector\SheadawsonLinkableFieldToSilverstripeLinkFieldRectorTest
 */
final class SheadawsonLinkableFieldToSilverstripeLinkFieldRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    /**
     * @readonly
     */
    private ArgsAnalyzer $argsAnalyzer;
    /**
     * @readonly
     */
    private ConfigurationResolver $configurationResolver;
    /**
     * @readonly
     */
    private NewFactory $newFactory;
    /**
     * @readonly
     */
    private Normaliser $normaliser;
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;
    public function __construct(ArgsAnalyzer $argsAnalyzer, ConfigurationResolver $configurationResolver, NewFactory $newFactory, Normaliser $normaliser, ValueResolver $valueResolver)
    {
        $this->argsAnalyzer = $argsAnalyzer;
        $this->configurationResolver = $configurationResolver;
        $this->newFactory = $newFactory;
        $this->normaliser = $normaliser;
        $this->valueResolver = $valueResolver;
    }

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

    public function getNodeTypes(): array
    {
        return [New_::class, StaticCall::class];
    }

    /**
     * @param New_|StaticCall $node
     */
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
            return $this->refactorMultiLinkField($node);
        }
        return null;
    }

    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
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

    /**
     * @param \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\StaticCall $node
     */
    private function refactorMultiLinkField($node): ?Node
    {
        $fieldNameArg = $node->getArgs()[0] ?? null;
        $fieldTitleArg = $node->getArgs()[1] ?? null;
        $relationArg = $node->getArgs()[2] ?? null;

        // Safety checks...
        if (!$fieldNameArg instanceof Arg) {
            return null;
        }

        if (!$relationArg instanceof Arg) {
            return null;
        }

        // Resolve the parent from $this->RelationName()
        if (!$relationArg->value instanceof MethodCall) {
            return null;
        }

        // Check that $this is a DataObject
        if (!$this->isObjectType($relationArg->value->var, new ObjectType('SilverStripe\ORM\DataObject'))) {
            return null;
        }

        // Grab the type of $this
        $parentClass = $this->getType($relationArg->value->var);

        // Safety check
        if ($parentClass->getObjectClassNames() === []) {
            return null;
        }

        $fieldName = $this->valueResolver->getValue($fieldNameArg);

        if (!is_string($fieldName)) {
            return null;
        }

        // Grab the `many_many` config
        $manyMany = $this->configurationResolver->get($parentClass->getObjectClassNames()[0], 'many_many');

        // Safety check
        if (!is_array($manyMany) || $manyMany === []) {
            return null;
        }

        // Check that the relation is in `many_many`
        if (!array_key_exists($fieldName, $manyMany)) {
            return null;
        }

        // Verify that the relation object is as expected
        /** @var string[] $manyMany */
        if ($this->normaliser->normaliseDotNotation($manyMany[$fieldName]) !== 'Sheadawson\Linkable\Models\Link') {
            return null;
        }

        // Build the args
        $args = [$fieldNameArg];

        // Add the title arg if it exists
        if ($fieldTitleArg instanceof Arg) {
            $args[] = $fieldTitleArg;
        }

        return $this->newFactory->createInjectable(
            'SilverStripe\LinkField\Form\MultiLinkField',
            $args,
            $node instanceof StaticCall
        );
    }
}
