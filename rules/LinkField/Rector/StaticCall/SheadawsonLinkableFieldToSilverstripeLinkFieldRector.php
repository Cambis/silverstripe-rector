<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\StaticCall;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\Normaliser\Normaliser;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
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
final class SheadawsonLinkableFieldToSilverstripeLinkFieldRector extends AbstractRector implements RelatedConfigInterface
{
    public function __construct(
        private readonly ConfigurationResolver $configurationResolver,
        private readonly Normaliser $normaliser,
        private readonly ValueResolver $valueResolver
    ) {
    }

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

    #[Override]
    public function getNodeTypes(): array
    {
        return [New_::class, StaticCall::class];
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

        if ($node instanceof StaticCall && !$this->isName($node->name, SilverstripeConstants::METHOD_CREATE)) {
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

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    private function refactorLinkField(New_|StaticCall $node): ?Node
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

        if ($node instanceof New_) {
            return $this->createNew(
                new FullyQualified('SilverStripe\LinkField\Form\LinkField'),
                $this->nodeFactory->createArgs($args),
            );
        }

        return $this->nodeFactory->createStaticCall(
            'SilverStripe\LinkField\Form\LinkField',
            'create',
            $this->nodeFactory->createArgs($args)
        );
    }

    private function refactorMultiLinkField(New_|StaticCall $node): ?Node
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

        $parentClass = $this->resolveParentClass($this->getType($relationArg->value->var));

        if (!$parentClass instanceof ClassReflection) {
            return null;
        }

        $fieldName = $this->valueResolver->getValue($fieldNameArg);

        if (!is_string($fieldName)) {
            return null;
        }

        $manyMany = $this->configurationResolver->get($parentClass->getName(), 'many_many');

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

        if ($node instanceof New_) {
            return $this->createNew(
                new FullyQualified('SilverStripe\LinkField\Form\MultiLinkField'),
                $this->nodeFactory->createArgs($args),
            );
        }

        return $this->nodeFactory->createStaticCall(
            'SilverStripe\LinkField\Form\MultiLinkField',
            'create',
            $this->nodeFactory->createArgs($args)
        );
    }

    /**
     * @param Expr|Name|string $class
     * @param Arg[] $args
     */
    private function createNew(mixed $class, array $args): New_
    {
        return new New_(BuilderHelpers::normalizeNameOrExpr($class), $args);
    }

    /**
     * Resolve the parent.
     */
    private function resolveParentClass(Type $type): ?ClassReflection
    {
        foreach ($type->getObjectClassReflections() as $classReflection) {
            if (!$classReflection->is('SilverStripe\ORM\DataObject')) {
                continue;
            }

            return $classReflection;
        }

        return null;
    }
}
