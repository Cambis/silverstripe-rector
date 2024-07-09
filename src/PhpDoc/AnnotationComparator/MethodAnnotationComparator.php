<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;

use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionGenericObjectType;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use SilverStripe\Core\Extension;

/**
 * @implements AnnotationComparatorInterface<MethodTagValueNode>
 */
final readonly class MethodAnnotationComparator implements AnnotationComparatorInterface
{
    public function __construct(
        private ClassAnalyser $classAnalyser,
        private NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard,
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    #[Override]
    public function getTagValueNodeClass(): string
    {
        return MethodTagValueNode::class;
    }

    #[Override]
    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $originalNode->methodName === $newNode->methodName;
    }

    #[Override]
    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        if (!$originalNode->returnType instanceof TypeNode) {
            return false;
        }

        if (!$newNode->returnType instanceof TypeNode) {
            return false;
        }

        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->returnType, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->returnType, $node);

        // Special case for `getOwner()`. We cast it as a custom generic object so that the types resolve correctly
        if ($this->isGetOwnerMethod($originalNode, $newNode, $node)) {
            $originalType = new ExtensionGenericObjectType(Extension::class, [$originalType]);
            $newType = new ExtensionGenericObjectType(Extension::class, [$newType]);
        }

        if (!$this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return false;
        }

        return !$this->typeComparator->areTypesEqual(
            $originalType,
            $newType
        );
    }

    private function isGetOwnerMethod(MethodTagValueNode $originalNode, MethodTagValueNode $newNode, Node $node): bool
    {
        if (!$node instanceof Class_) {
            return false;
        }

        if (!$this->classAnalyser->isExtension($node)) {
            return false;
        }

        if ($originalNode->methodName !== SilverstripeConstants::METHOD_GET_OWNER) {
            return false;
        }

        return $newNode->methodName === SilverstripeConstants::METHOD_GET_OWNER;
    }
}
