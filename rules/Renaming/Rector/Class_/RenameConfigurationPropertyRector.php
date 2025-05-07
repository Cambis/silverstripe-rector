<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\Rector\Class_;

use Cambis\SilverstripeRector\NodeFactory\PropertyFactory;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameConfigurationProperty;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function is_string;

/**
 * @see \Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\RenameConfigurationPropertyRectorTest
 */
final class RenameConfigurationPropertyRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var list<RenameConfigurationProperty>
     */
    private array $renameProperties = [];

    private bool $hasChanged = false;

    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly PropertyFactory $propertyFactory,
        private readonly ValueResolver $valueResolver
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename a configuration property.', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $description = '';
}

\SilverStripe\Config\Collections\MemoryConfigCollection::get('Foo', 'description');
Foo::config()->get('description');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $class_description = '';
}

\SilverStripe\Config\Collections\MemoryConfigCollection::get('Foo', 'class_description');
Foo::config()->get('class_description');
CODE_SAMPLE,
            [new RenameConfigurationProperty('SilverStripe\ORM\DataObject', 'description', 'class_description')]
        ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [Class_::class,  MethodCall::class, StaticPropertyFetch::class];
    }

    /**
     * @param Class_|MethodCall|StaticPropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        if ($node instanceof StaticPropertyFetch) {
            return $this->refactorStaticPropertyFetch($node);
        }

        $this->hasChanged = false;

        foreach ($this->renameProperties as $renameConfigurationProperty) {
            $this->renameProperty($node, $renameConfigurationProperty);
        }

        if (!$this->hasChanged) {
            return null;
        }

        return $node;
    }

    public function configure(array $configuration): void
    {
        foreach ($configuration as $value) {
            if (!$value instanceof RenameConfigurationProperty) {
                throw new InvalidArgumentException(self::class . ' only accepts ' . RenameConfigurationProperty::class);
            }
        }

        /** @var list<RenameConfigurationProperty> $configuration */
        $this->renameProperties = $configuration;
    }

    private function refactorStaticPropertyFetch(StaticPropertyFetch $staticPropertyFetch): ?StaticPropertyFetch
    {
        foreach ($this->renameProperties as $renameProperty) {
            if (!$this->isName($staticPropertyFetch, $renameProperty->oldPropertyName)) {
                continue;
            }

            if (!$this->isObjectType($staticPropertyFetch->class, new ObjectType($renameProperty->extensibleClassName))) {
                continue;
            }

            $staticPropertyFetch->name = new VarLikeIdentifier($renameProperty->newPropertyName);

            return $staticPropertyFetch;
        }

        return null;
    }

    private function refactorMethodCall(MethodCall $methodCall): ?MethodCall
    {
        if ($this->argsAnalyzer->hasNamedArg($methodCall->getArgs())) {
            return null;
        }

        if ($this->isObjectType($methodCall->var, new ObjectType('SilverStripe\Config\Collections\ConfigCollectionInterface'))) {
            return $this->refactorConfigCollectionInterface($methodCall);
        }

        if ($this->isObjectType($methodCall->var, new ObjectType('SilverStripe\Core\Config\Config_ForClass'))) {
            return $this->refactorConfigForClass($methodCall);
        }

        return null;
    }

    private function refactorConfigCollectionInterface(MethodCall $methodCall): ?MethodCall
    {
        if (!$this->isNames($methodCall->name, ['get', 'merge', 'remove', 'set'])) {
            return null;
        }

        $classNameArg = $methodCall->getArgs()[0] ?? null;
        $propertyNameArg = $methodCall->getArgs()[1] ?? null;

        if (!$classNameArg instanceof Arg || !$propertyNameArg instanceof Arg) {
            return null;
        }

        $className = $this->valueResolver->getValue($classNameArg, true);
        $propertyName = $this->valueResolver->getValue($propertyNameArg);

        if (!is_string($className)) {
            return null;
        }

        foreach ($this->renameProperties as $renameProperty) {
            if ((new ObjectType($renameProperty->extensibleClassName))->isSuperTypeOf(new ObjectType($className))->no()) {
                continue;
            }

            if ($propertyName !== $renameProperty->oldPropertyName) {
                continue;
            }

            $args = [...$methodCall->getArgs()];
            $args[1] = $this->nodeFactory->createArg($renameProperty->newPropertyName);

            return $this->nodeFactory->createMethodCall(
                $methodCall->var,
                $this->getName($methodCall->name) ?? 'get',
                $args
            );
        }

        return null;
    }

    private function refactorConfigForClass(MethodCall $methodCall): ?MethodCall
    {
        if (!$this->isNames($methodCall->name, ['get', 'merge', 'remove', 'set', 'uninherited'])) {
            return null;
        }

        $propertyNameArg = $methodCall->getArgs()[0] ?? null;

        if (!$propertyNameArg instanceof Arg) {
            return null;
        }

        // Attempt to resolve the type of the var
        if (!$methodCall->var instanceof StaticCall && !$methodCall->var instanceof MethodCall) {
            return null;
        }

        $type = $methodCall->var instanceof StaticCall ? $this->getType($methodCall->var->class) : $this->getType($methodCall->var->var);
        $propertyName = $this->valueResolver->getValue($propertyNameArg);

        foreach ($this->renameProperties as $renameProperty) {
            if ((new ObjectType($renameProperty->extensibleClassName))->isSuperTypeOf($type)->no()) {
                continue;
            }

            if ($propertyName !== $renameProperty->oldPropertyName) {
                continue;
            }

            $args = [...$methodCall->getArgs()];
            $args[0] = $this->nodeFactory->createArg($renameProperty->newPropertyName);

            return $this->nodeFactory->createMethodCall(
                $methodCall->var,
                $this->getName($methodCall->name) ?? 'get',
                $args
            );
        }

        return null;
    }

    private function renameProperty(Class_ $class, RenameConfigurationProperty $renameConfigurationProperty): void
    {
        if (!$this->isObjectType($class, new ObjectType($renameConfigurationProperty->extensibleClassName))) {
            return;
        }

        $property = $this->propertyFactory->findConfigurationProperty($class, $renameConfigurationProperty->oldPropertyName);

        if (!$property instanceof Property) {
            return;
        }

        $newProperty = $this->propertyFactory->findConfigurationProperty($class, $renameConfigurationProperty->newPropertyName);

        if ($newProperty instanceof Property) {
            return;
        }

        $property->props[0]->name = new VarLikeIdentifier($renameConfigurationProperty->newPropertyName);
        $this->hasChanged = true;
    }
}
