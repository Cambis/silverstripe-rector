<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use Override;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @implements AnnotationComparatorInterface<PropertyTagValueNode>
 */
final readonly class PropertyAnnotationComparator implements AnnotationComparatorInterface
{
    public function __construct(
        private NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard,
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    #[Override]
    public function getTagValueNodeClass(): string
    {
        return PropertyTagValueNode::class;
    }

    #[Override]
    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        return $originalNode->propertyName === $newNode->propertyName;
    }

    #[Override]
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
