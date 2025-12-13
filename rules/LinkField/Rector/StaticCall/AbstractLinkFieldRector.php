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
use function array_key_exists;
use function in_array;
use function is_array;
use function is_string;

abstract class AbstractLinkFieldRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    public function __construct(
        protected readonly ArgsAnalyzer $argsAnalyzer,
        protected readonly ConfigurationResolver $configurationResolver,
        protected readonly NewFactory $newFactory,
        protected readonly Normaliser $normaliser,
        protected readonly ValueResolver $valueResolver
    ) {
    }

    final public function getNodeTypes(): array
    {
        return [New_::class, StaticCall::class];
    }

    final public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    final protected function refactorGridField(New_|StaticCall $node): ?Node
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
        if (!in_array($this->normaliser->normaliseDotNotation($manyMany[$fieldName]), ['gorriecoe\Link\Models\Link', 'Sheadawson\Linkable\Models\Link'], true)) {
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
