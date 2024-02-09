<?php

declare(strict_types=1);

namespace SilverstripeRector\DocBlock;

use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class DocBlockHelper
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper
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
}
