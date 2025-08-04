<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\CodeQuality\Rector\Assign;

use Cambis\SilverstripeRector\NodeAnalyser\StaticPropertyFetchAnalyser;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Configuration\Parameter\FeatureFlags;
use Rector\Enum\ObjectReference;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\ConfigurationPropertyFetchToMethodCallRectorTest
 */
final class ConfigurationPropertyFetchToMethodCallRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private StaticPropertyFetchAnalyser $staticPropertyFetchAnalyser;
    public function __construct(StaticPropertyFetchAnalyser $staticPropertyFetchAnalyser)
    {
        $this->staticPropertyFetchAnalyser = $staticPropertyFetchAnalyser;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Transforms a property fetch on a configuration property into an appropriate getter/setter call.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $bar = '';

    public function doSomething(): void
    {
        self::$bar = 'baz';

        echo self::$bar;
    }
}

Foo::config()->bar = 'baz';

echo Foo::config()->bar;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $bar = '';

    public function doSomething(): void
    {
        self::config()->set('bar', 'baz');

        echo self::config()->get('bar');
    }
}

Foo::config()->set('bar', 'baz');

echo Foo::config()->get('bar');
CODE_SAMPLE
            ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Assign::class, PropertyFetch::class, StaticPropertyFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Assign && $node->var instanceof PropertyFetch) {
            return $this->refactorDynamicAssign($node);
        }

        if ($node instanceof Assign && $node->var instanceof StaticPropertyFetch) {
            return $this->refactorStaticAssign($node);
        }

        if ($node instanceof PropertyFetch) {
            return $this->refactorPropertyFetch($node);
        }

        if ($node instanceof StaticPropertyFetch) {
            return $this->refactorStaticPropertyFetch($node);
        }

        return null;
    }

    private function refactorDynamicAssign(Assign $assign): ?MethodCall
    {
        if (!$assign->var instanceof PropertyFetch) {
            return null;
        }

        if (!$this->isObjectType($assign->var->var, new ObjectType('SilverStripe\Core\Config\Config_ForClass'))) {
            return null;
        }

        $propertyName = $this->getName($assign->var->name) ?? '';

        if ($propertyName === '') {
            return null;
        }

        return $this->nodeFactory->createMethodCall($assign->var->var, 'set', $this->nodeFactory->createArgs([$propertyName, $assign->expr]));
    }

    private function refactorStaticAssign(Assign $assign): ?MethodCall
    {
        if (!$assign->var instanceof StaticPropertyFetch) {
            return null;
        }

        if (!$this->staticPropertyFetchAnalyser->isConfigurationProperty($assign->var)) {
            return null;
        }

        /** @var non-empty-string $propertyName */
        $propertyName = $this->getName($assign->var->name);
        $configCall = $this->nodeFactory->createStaticCall($this->getName($assign->var->class) ?? $this->getFallbackName($assign->var), 'config');

        return $this->nodeFactory->createMethodCall($configCall, 'set', $this->nodeFactory->createArgs([$propertyName, $assign->expr]));
    }

    private function refactorPropertyFetch(PropertyFetch $propertyFetch): ?MethodCall
    {
        if (!$this->isObjectType($propertyFetch->var, new ObjectType('SilverStripe\Core\Config\Config_ForClass'))) {
            return null;
        }

        $propertyName = $this->getName($propertyFetch->name) ?? '';

        if ($propertyName === '') {
            return null;
        }

        return $this->nodeFactory->createMethodCall($propertyFetch->var, 'get', $this->nodeFactory->createArgs([$propertyName]));
    }

    private function refactorStaticPropertyFetch(StaticPropertyFetch $staticPropertyFetch): ?MethodCall
    {
        if (!$this->staticPropertyFetchAnalyser->isConfigurationProperty($staticPropertyFetch)) {
            return null;
        }

        /** @var non-empty-string $propertyName */
        $propertyName = $this->getName($staticPropertyFetch->name);
        $configCall = $this->nodeFactory->createStaticCall($this->getName($staticPropertyFetch->class) ?? $this->getFallbackName($staticPropertyFetch), 'config');

        return $this->nodeFactory->createMethodCall($configCall, 'get', [$propertyName]);
    }

    private function getFallbackName(StaticPropertyFetch $staticPropertyFetch): string
    {
        $scope = ScopeFetcher::fetch($staticPropertyFetch);

        if (!$scope->isInClass()) {
            return ObjectReference::STATIC;
        }

        $classReflection = $scope->getClassReflection();

        return $classReflection->isFinal() || FeatureFlags::treatClassesAsFinal() ? ObjectReference::SELF : ObjectReference::STATIC;
    }
}
