<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use Override;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<TemplateTagValueNode>
 */
final readonly class TemplateAnnotationComparator implements AnnotationComparatorInterface
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    #[Override]
    public function getTagValueNodeClass(): string
    {
        return TemplateTagValueNode::class;
    }

    #[Override]
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

    #[Override]
    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return false;
    }
}
