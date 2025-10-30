<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\CodeQuality\Rector\PropertyFetch;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\PropertyFetch\ExtensionOnwerToGetOwnerRector\ExtensionOwnerToGetOwnerRectorTest
 */
final class ExtensionOwnerToGetOwnerRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change `Extension::$owner` to use `Extension::getOwner()` instead.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
    protected function doSomething(): void
    {
        $this->owner->doSomethingElse();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
    protected function doSomething(): void
    {
        $this->getOwner()->doSomethingElse();
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'owner')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\Core\Extension'))) {
            return null;
        }

        return $this->nodeFactory->createMethodCall($node->var, 'getOwner');
    }
}
