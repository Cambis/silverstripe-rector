<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use Override;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<MixinTagValueNode>
 */
final readonly class MixinAnnotationComparator implements AnnotationComparatorInterface
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    #[Override]
    public function getTagValueNodeClass(): string
    {
        return MixinTagValueNode::class;
    }

    #[Override]
    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $this->typeComparator->areTypesEqual(
            $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $originalNode->type,
                $node
            ),
            $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $newNode->type,
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
