<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;

use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use Cambis\SilverstripeRector\StaticTypeMapper\PhpDocParser\GenericTypeMapper;
use Override;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\Generic\GenericObjectType;
use Rector\BetterPhpDocParser\Guard\NewPhpDocFromPHPStanTypeGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Rector\StaticTypeMapper\StaticTypeMapper;
use function count;

/**
 * @implements AnnotationComparatorInterface<ExtendsTagValueNode>
 */
final readonly class ExtendsAnnotationComparator implements AnnotationComparatorInterface
{
    public function __construct(
        private GenericTypeMapper $genericTypeMapper,
        private NameScopeFactory $nameScopeFactory,
        private NewPhpDocFromPHPStanTypeGuard $newPhpDocFromPHPStanTypeGuard,
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    #[Override]
    public function getTagValueNodeClass(): string
    {
        return ExtendsTagValueNode::class;
    }

    #[Override]
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

    #[Override]
    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($node);

        $originalType = $this->genericTypeMapper->mapToPHPStanType($originalNode->type, $node, $nameScope);
        $newType = $this->genericTypeMapper->mapToPHPStanType($newNode->type, $node, $nameScope);

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
