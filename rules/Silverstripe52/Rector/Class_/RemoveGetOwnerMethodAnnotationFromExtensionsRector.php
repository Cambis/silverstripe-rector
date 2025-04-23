<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\RemoveGetOwnerMethodAnnotationFromExtensionsRectorTest
 */
final class RemoveGetOwnerMethodAnnotationFromExtensionsRector extends AbstractRector implements DocumentedRuleInterface
{
    public function __construct(
        private readonly ClassAnalyser $classAnalyser,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove getOwner() method annotation.', [new CodeSample(
            <<<'CODE_SAMPLE'
/**
 * @method getOwner() $this
 */
class Foo extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
        ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    #[Override]
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (!$this->classAnalyser->isExtension($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $hasChanged = false;
        $phpDocNodeTraverser = new PhpDocNodeTraverser();

        $phpDocNodeTraverser->traverseWithCallable($phpDocInfo->getPhpDocNode(), '', static function (AstNode $node) use (&$hasChanged): ?int {
            if (!$node instanceof PhpDocTagNode) {
                return null;
            }

            if (!$node->value instanceof MethodTagValueNode) {
                return null;
            }

            if ($node->value->methodName !== 'getOwner') {
                return null;
            }

            $hasChanged = true;

            return PhpDocNodeTraverser::NODE_REMOVE;
        });

        if (!$hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }
}
