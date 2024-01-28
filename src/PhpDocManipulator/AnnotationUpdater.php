<?php

declare(strict_types=1);

namespace SilverstripeRector\PhpDocManipulator;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use function count;

final class AnnotationUpdater
{
    public function __construct(
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeComparator $typeComparator
    ) {
    }

    /**
     * Check if a similar annotation exists. Update the type if needed.
     */
    public function hasExistingAnnotation(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        if (
            $originalNode instanceof PropertyTagValueNode &&
            $newNode instanceof PropertyTagValueNode &&
            $originalNode->propertyName === $newNode->propertyName
        ) {
            if ($this->shouldUpdateProperty($originalNode, $newNode, $node)) {
                $originalNode->type = $newNode->type;
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
            }

            return true;
        }

        if (
            $originalNode instanceof MethodTagValueNode &&
            $newNode instanceof MethodTagValueNode &&
            $originalNode->methodName === $newNode->methodName
        ) {
            if ($this->shouldUpdateMethod($originalNode, $newNode, $node)) {
                $originalNode->returnType = $newNode->returnType;
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
            }

            return true;
        }

        if (
            $originalNode instanceof MixinTagValueNode &&
            $newNode instanceof MixinTagValueNode &&
            $this->shouldCheckMixin($originalNode, $newNode, $node)
        ) {
            return true;
        }

        if (
            $originalNode instanceof ExtendsTagValueNode &&
            $newNode instanceof ExtendsTagValueNode &&
            $this->shouldCheckExtends($originalNode, $newNode, $node)
        ) {
            if ($this->shouldUpdateExtends($originalNode, $newNode, $node)) {
                $originalNode->type = $newNode->type;
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
            }

            return true;
        }

        return false;
    }

    private function shouldUpdateProperty(PropertyTagValueNode $originalNode, PropertyTagValueNode $newNode, Node $node): bool
    {
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

    private function shouldUpdateMethod(MethodTagValueNode $originalNode, MethodTagValueNode $newNode, Node $node): bool
    {
        if (!$originalNode->returnType instanceof TypeNode) {
            return false;
        }

        if (!$newNode->returnType instanceof TypeNode) {
            return false;
        }

        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->returnType, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->returnType, $node);

        if (!$this->newPhpDocFromPHPStanTypeGuard->isLegal($newType)) {
            return false;
        }

        return !$this->typeComparator->areTypesEqual(
            $originalType,
            $newType
        );
    }

    private function shouldCheckMixin(MixinTagValueNode $originalNode, MixinTagValueNode $newNode, Node $node): bool
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

    private function shouldCheckExtends(ExtendsTagValueNode $originalNode, ExtendsTagValueNode $newNode, Node $node): bool
    {
        $originalType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($originalNode->type, $node);
        $newType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($newNode->type, $node);

        if (!$originalType instanceof GenericObjectType) {
            return false;
        }

        if (!$newType instanceof GenericObjectType) {
            return false;
        }

        return $originalType->getClassName() === $originalType->getClassName();
    }

    private function shouldUpdateExtends(ExtendsTagValueNode $originalNode, ExtendsTagValueNode $newNode, Node $node): bool
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
            $originalType->getTypes()[0],
            $newType->getTypes()[0]
        );
    }
}
