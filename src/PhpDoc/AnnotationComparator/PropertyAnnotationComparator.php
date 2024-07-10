<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;

use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<PropertyTagValueNode>
 */
final class PropertyAnnotationComparator implements AnnotationComparatorInterface
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
        return PropertyTagValueNode::class;
    }

    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $originalNode->propertyName === $newNode->propertyName;
    }

    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        if ($originalNode->propertyName !== $newNode->propertyName) {
            return false;
        }
        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->type, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->type, $node);
        if (!$this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return false;
        }
        return !$this->typeComparator->areTypesEqual(
            $originalType,
            $newType
        );
    }
}
