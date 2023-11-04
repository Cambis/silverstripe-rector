<?php

declare(strict_types=1);

namespace SilverstripeRector\DocBlock;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class DocBlockHelper
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeComparator $typeComparator
    ) {
    }

    /**
     * @param Type[] $paramsNameToType
     * @return PhpDocTagValueNode[]
     */
    public function convertTypesToPropertyTagValueNodes(array $paramsNameToType): array
    {
        $result = [];

        if ($paramsNameToType === []) {
            return $result;
        }

        foreach ($paramsNameToType as $name => $type) {
            $result[] = new PropertyTagValueNode(
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type),
                $name,
                ''
            );
        }

        return $result;
    }

    /**
     * @param Type[] $paramsNameToType
     * @return PhpDocTagValueNode[]
     */
    public function convertTypesToMethodTagValueNodes(array $paramsNameToType): array
    {
        $result = [];

        if ($paramsNameToType === []) {
            return $result;
        }

        foreach ($paramsNameToType as $name => $type) {
            $result[] = new MethodTagValueNode(
                false,
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type),
                $name,
                [],
                '',
                []
            );
        }

        return $result;
    }

    /**
     * @param Type[] $params
     * @return PhpDocTagValueNode[]
     */
    public function convertTypesToMixinTagValueNodes(array $params): array
    {
        $result = [];

        if ($params === []) {
            return $result;
        }

        foreach ($params as $type) {
            $result[] = new MixinTagValueNode(
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type),
                '',
            );
        }

        return $result;
    }

    /**
     * @param PhpDocTagValueNode[] $newDocTagValueNodes
     * @return PhpDocTagValueNode[]
     */
    public function filterOutExistingAnnotations(Node $node, PhpDocInfo $phpDocInfo, array $newDocTagValueNodes): array
    {
        return array_filter(
            $newDocTagValueNodes,
            function (PhpDocTagValueNode $newDocTagValueNode) use ($node, $phpDocInfo): bool {
                foreach ($phpDocInfo->getPhpDocNode()->children as $phpDocTagNode) {
                    if (!$phpDocTagNode instanceof PhpDocTagNode) {
                        continue;
                    }

                    $phpDocTagValueNode = $phpDocTagNode->value;

                    if (!$phpDocTagValueNode instanceof PhpDocTagValueNode) {
                        continue;
                    }

                    if (
                        $phpDocTagValueNode instanceof PropertyTagValueNode &&
                        $newDocTagValueNode instanceof PropertyTagValueNode &&
                        $phpDocTagValueNode->propertyName === $newDocTagValueNode->propertyName
                    ) {
                        return false;
                    }

                    if (
                        $phpDocTagValueNode instanceof MethodTagValueNode &&
                        $newDocTagValueNode instanceof MethodTagValueNode &&
                        $phpDocTagValueNode->methodName === $newDocTagValueNode->methodName
                    ) {
                        return false;
                    }

                    if (!$phpDocTagValueNode instanceof MixinTagValueNode) {
                        continue;
                    }

                    if (!$newDocTagValueNode instanceof MixinTagValueNode) {
                        continue;
                    }

                    if (!$this->typeComparator->areTypesEqual(
                        $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                            $phpDocTagValueNode->type,
                            $node
                        ),
                        $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                            $newDocTagValueNode->type,
                            $node
                        ),
                    )) {
                        continue;
                    }

                    return false;
                }

                return true;
            }
        );
    }
}
