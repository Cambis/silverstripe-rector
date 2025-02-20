<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;

use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<TemplateTagValueNode>
 */
final class TemplateAnnotationComparator implements AnnotationComparatorInterface
{
    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;
    /**
     * @readonly
     */
    private TypeComparator $typeComparator;
    public function __construct(StaticTypeMapper $staticTypeMapper, TypeComparator $typeComparator)
    {
        $this->staticTypeMapper = $staticTypeMapper;
        $this->typeComparator = $typeComparator;
    }

    public function getTagValueNodeClass(): string
    {
        return TemplateTagValueNode::class;
    }

    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $this->typeComparator->areTypesEqual(
            $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType(
                $originalNode,
                $node
            ),
            $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType(
                $newNode,
                $node
            ),
        );
    }

    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return false;
    }
}
