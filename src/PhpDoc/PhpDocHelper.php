<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc;

use PhpParser\Node\NullableType;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Type;
use Rector\Enum\ObjectReference;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class PhpDocHelper
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
            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type);

            $phpParserNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PROPERTY);

            if ($typeNode instanceof UnionTypeNode && $phpParserNode instanceof NullableType) {
                $typeNode = new NullableTypeNode($typeNode->types[1]);
            }

            if ($typeNode instanceof IntersectionTypeNode || $typeNode instanceof UnionTypeNode) {
                $typeNode = $this->fixCompoundTypeNode($typeNode);
            }

            $result[] = new PropertyTagValueNode(
                $typeNode,
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
            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type);

            if ($typeNode instanceof IntersectionTypeNode || $typeNode instanceof UnionTypeNode) {
                $typeNode = $this->fixCompoundTypeNode($typeNode);
            }

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

    /**
     * `\Rector\PHPStanStaticTypeMapper\TypeMapper\IntersectionTypeMapper::mapToPHPStanPhpDocTypeNode()` will turn `static` into `\static`.
     * Remove the leading slash from `\static`.
     */
    private function fixCompoundTypeNode(IntersectionTypeNode|UnionTypeNode $typeNode): TypeNode
    {
        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($typeNode, '', static function (Node $astNode): ?IdentifierTypeNode {
            if ($astNode instanceof IdentifierTypeNode) {
                if ($astNode->name !== '\\' . ObjectReference::STATIC) {
                    return $astNode;
                }

                $astNode->name = ObjectReference::STATIC;

                return $astNode;
            }

            return null;
        });

        return $typeNode;
    }
}
