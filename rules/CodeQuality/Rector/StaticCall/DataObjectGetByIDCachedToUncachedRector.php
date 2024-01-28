<?php

declare(strict_types=1);

namespace SilverstripeRector\CodeQuality\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use SilverStripe\ORM\DataObject;
use SilverstripeRector\ValueObject\SilverstripeConstants;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function is_string;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/issues/5976
 * @see \SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\DataObjectGetByIDCachedToUncachedRectorTest
 */
final class DataObjectGetByIDCachedToUncachedRector extends AbstractRector
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change DataObject::get_by_id() to use DataObject::get()->byID() instead.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$foo = \SilverStripe\Assets\File::get_by_id(1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$foo = \SilverStripe\Assets\File::get()->byID(1);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipStaticCall($node)) {
            return null;
        }

        $className = (string) $this->getName($node->class);
        $dataListCall = $this->nodeFactory->createStaticCall($className, SilverstripeConstants::METHOD_GET, []);

        return $this->nodeFactory->createMethodCall($dataListCall, SilverstripeConstants::METHOD_BY_ID, $node->args);
    }

    private function shouldSkipStaticCall(StaticCall $staticCall): bool
    {
        if (!$this->isName($staticCall->name, SilverstripeConstants::METHOD_GET_BY_ID)) {
            return true;
        }

        $className = $this->getName($staticCall->class);

        if (!is_string($className)) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return !$classReflection->isSubclassOf(DataObject::class);
    }
}
