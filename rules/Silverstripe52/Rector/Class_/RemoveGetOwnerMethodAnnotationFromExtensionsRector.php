<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\RemoveGetOwnerMethodAnnotationFromExtensionsRectorTest
 */
final class RemoveGetOwnerMethodAnnotationFromExtensionsRector extends AbstractRector
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ReflectionProvider $reflectionProvider,
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
        if ($this->shouldSkipClass($node)) {
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

            if ($node->value->methodName !== SilverstripeConstants::METHOD_GET_OWNER) {
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

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $className = $this->nodeNameResolver->getName($class);

        if ($className === null) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return !$classReflection->isSubclassOf('SilverStripe\Core\Extension');
    }
}
