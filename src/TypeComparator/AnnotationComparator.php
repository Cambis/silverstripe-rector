<?php

declare(strict_types=1);

namespace SilverstripeRector\TypeComparator;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class AnnotationComparator
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeComparator $typeComparator
    ) {
    }

    public function shouldSkipNewAnnotation(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        if (
            $originalNode instanceof PropertyTagValueNode &&
            $newNode instanceof PropertyTagValueNode &&
            $originalNode->propertyName === $newNode->propertyName
        ) {
            return true;
        }

        if (
            $originalNode instanceof MethodTagValueNode &&
            $newNode instanceof MethodTagValueNode &&
            $originalNode->methodName === $newNode->methodName
        ) {
            return true;
        }

        if (
            $originalNode instanceof MixinTagValueNode &&
            $newNode instanceof MixinTagValueNode &&
            $this->typeComparator->areTypesEqual(
                $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                    $originalNode->type,
                    $node
                ),
                $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                    $newNode->type,
                    $node
                ),
            )
        ) {
            return true;
        }

        return false;
    }
}
