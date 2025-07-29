<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function is_string;

/**
 * @deprecated 2.1.0 use Cambis\SilverstripeRector\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector instead.
 *
 * @see \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector\StaticPropertyFetchToConfigGetRectorTest
 */
final class StaticPropertyFetchToConfigGetRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ReflectionResolver $reflectionResolver;
    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Transforms static property fetch into `$this->config->get()`.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $singular_name = 'Foo';

    public function getType(): string
    {
        return self::$singular_name;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static string $singular_name = 'Foo';

    public function getType(): string
    {
        return $this->config()->get('singular_name');
    }
}
CODE_SAMPLE
            ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticPropertyFetch::class];
    }

    /**
     * @param StaticPropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (!$classReflection instanceof ClassReflection) {
            return null;
        }
        if ($this->shouldSkipClass($classReflection)) {
            return null;
        }
        $propertyFetchScope = $node->getAttribute(AttributeKey::SCOPE);
        if (!$propertyFetchScope instanceof Scope) {
            return null;
        }
        $propertyName = $this->nodeNameResolver->getName($node->name);
        if (!is_string($propertyName)) {
            return null;
        }
        $propertyReflection = $classReflection->getProperty($propertyName, $propertyFetchScope);
        if ($this->shouldSkipProperty($propertyReflection)) {
            return null;
        }
        $configCall = $this->nodeFactory->createMethodCall('this', 'config');
        return $this->nodeFactory->createMethodCall($configCall, 'get', [$propertyName]);
    }

    private function shouldSkipClass(ClassReflection $classReflection): bool
    {
        if ($classReflection->is('SilverStripe\Core\Extension')) {
            return false;
        }

        return !$classReflection->hasTraitUse('SilverStripe\Core\Config\Configurable');
    }

    private function shouldSkipProperty(PropertyReflection $propertyReflection): bool
    {
        if (!$propertyReflection->isPrivate()) {
            return true;
        }

        if (!$propertyReflection->isStatic()) {
            return true;
        }

        return strpos((string) $propertyReflection->getDocComment(), '@internal') !== false;
    }
}
