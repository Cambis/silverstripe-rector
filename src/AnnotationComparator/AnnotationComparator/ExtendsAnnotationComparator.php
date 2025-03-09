<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\Generic\GenericObjectType;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use function count;

/**
 * @implements AnnotationComparatorInterface<ExtendsTagValueNode>
 */
final class ExtendsAnnotationComparator implements AnnotationComparatorInterface
{
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
    public function __construct(NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard, StaticTypeMapper $staticTypeMapper, TypeComparator $typeComparator)
    {
        $this->newPhpDocFromPHPStanTypeGuard = $newPhpDocFromPHPStanTypeGuard;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->typeComparator = $typeComparator;
    }

    public function getTagValueNodeClass(): string
    {
        return ExtendsTagValueNode::class;
    }

    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->type, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->type, $node);
        if (!$originalType instanceof GenericObjectType) {
            return false;
        }
        if (!$newType instanceof GenericObjectType) {
            return false;
        }
        return $originalType->getClassName() === $newType->getClassName();
    }

    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->type, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->type, $node);
        if (!$this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return false;
        }
        if (!$originalType instanceof GenericObjectType) {
            return false;
        }
        if (!$newType instanceof GenericObjectType) {
            return false;
        }
        // There should only be one type here
        if (count($originalType->getTypes()) !== 1) {
            return false;
        }
        if (count($newType->getTypes()) !== 1) {
            return false;
        }
        return !$this->typeComparator->areTypesEqual(
            $originalType,
            $newType
        );
    }
}
