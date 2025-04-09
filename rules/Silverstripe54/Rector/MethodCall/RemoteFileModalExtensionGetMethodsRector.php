<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/silverstripe/silverstripe-asset-admin/commit/e8bd854105ec44de0e4b1432d081cc5fc0a77b07
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector\RemoteFileModalExtensionGetMethodsRectorTest
 */
final class RemoteFileModalExtensionGetMethodsRector extends AbstractRector
{
    /**
     * @var list<SilverstripeConstants::METHOD_*>
     */
    private const METHOD_NAMES = [
        SilverstripeConstants::METHOD_GET_REQUEST,
        SilverstripeConstants::METHOD_GET_SCHEMA_RESPONSE,
    ];

    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `RemoteModalFileExtension::getRequest()` to `RemoteModalFileExtension::getOwner()->getRequest()` and `RemoteModalFileExtension::getSchemaResponse()` to `RemoteModalFileExtension::getOwner()->getSchemaResponse()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension
{
    public function doSomething(): void
    {
        $this->getRequest();
        $this->getSchemaResponse('schema');
    }         
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension
{
    public function doSomething(): void
    {
        $this->getOwner()->getRequest();
        $this->getOwner()->getSchemaRespons('schema');
    }         
}
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
        if (!$this->isObjectType($node->var, new ObjectType('SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension'))) {
            return null;
        }

        if (!$this->isNames($node->name, self::METHOD_NAMES)) {
            return null;
        }

        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        $getOwnerCall = $this->nodeFactory->createMethodCall($node->var, SilverstripeConstants::METHOD_GET_OWNER);
        $methodName = $this->nodeNameResolver->getName($node->name);

        if ($methodName === null) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $getOwnerCall,
            $methodName,
            $node->getArgs(),
        );
    }
}
