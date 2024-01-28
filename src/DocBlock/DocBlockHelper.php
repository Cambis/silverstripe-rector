<?php

declare(strict_types=1);

namespace SilverstripeRector\DocBlock;

use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class DocBlockHelper
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
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
     * @param TypeNode[] $paramsNameToType
     * @return PhpDocTagValueNode[]
     */
    public function convertTypeNodesToMethodTagValueNodes(array $paramsNameToType): array
    {
        $result = [];

        if ($paramsNameToType === []) {
            return $result;
        }

        foreach ($paramsNameToType as $name => $typeNode) {
            $result[] = new MethodTagValueNode(
                false,
                $typeNode,
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
}
