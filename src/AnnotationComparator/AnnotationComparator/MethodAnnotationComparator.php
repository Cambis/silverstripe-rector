<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<MethodTagValueNode>
 */
final class MethodAnnotationComparator implements AnnotationComparatorInterface
{
    /**
     * @readonly
     */
    private ClassAnalyser $classAnalyser;
    /**
     * @readonly
     */
    private NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard;
    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;
    /**
     * @readonly
     */
    private TypeComparator $typeComparator;
    public function __construct(ClassAnalyser $classAnalyser, NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard, StaticTypeMapper $staticTypeMapper, TypeComparator $typeComparator)
    {
        $this->classAnalyser = $classAnalyser;
        $this->newPhpDocFromPHPStanTypeGuard = $newPhpDocFromPHPStanTypeGuard;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->typeComparator = $typeComparator;
    }

    public function getTagValueNodeClass(): string
    {
        return MethodTagValueNode::class;
    }

    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $originalNode->methodName === $newNode->methodName;
    }

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
            $originalType = new GenericObjectType('SilverStripe\Core\Extension', [$originalType]);
            $newType = new GenericObjectType('SilverStripe\Core\Extension', [$newType]);
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

        if ($originalNode->methodName !== 'getOwner') {
            return false;
        }

        return $newNode->methodName === 'getOwner';
    }
}
