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
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\Enum\ObjectReference;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use function str_starts_with;

final class PhpDocHelper
{
    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;
    public function __construct(StaticTypeMapper $staticTypeMapper)
    {
        $this->staticTypeMapper = $staticTypeMapper;
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
                $typeNode = new NullableTypeNode($typeNode->types[0]);
            }

            if ($typeNode instanceof IntersectionTypeNode || $typeNode instanceof UnionTypeNode) {
                $typeNode = $this->fixCompoundTypeNode($typeNode);
            }

            $result[] = new PropertyTagValueNode(
                $typeNode,
                strncmp('$', $name, strlen($name)) === 0 ? $name : '$' . $name,
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
            $type = $this->transformObjectTypeIntoFullyQualifiedObjectType($type);
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
            $type = $this->transformObjectTypeIntoFullyQualifiedObjectType($type);

            $result[] = new MixinTagValueNode(
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type),
                '',
            );
        }

        return $result;
    }

    /**
     * Transform `ObjectType` into `FullyQualifiedObjectType`, this ensures that there is a leading slash when the type is converted into a `TypeNode`.
     */
    public function transformObjectTypeIntoFullyQualifiedObjectType(Type $type): Type
    {
        return TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            if (!$type instanceof ObjectType) {
                return $traverse($type);
            }

            if ($type instanceof GenericObjectType) {
                return $traverse($type);
            }

            // Already a `FullyQualifiedObjecType`, return
            if ($type instanceof FullyQualifiedObjectType) {
                return $type;
            }

            return new FullyQualifiedObjectType($type->getClassName());
        });
    }

    /**
     * `\Rector\PHPStanStaticTypeMapper\TypeMapper\IntersectionTypeMapper::mapToPHPStanPhpDocTypeNode()` will turn `static` into `\static`.
     * Remove the leading slash from `\static`.
     * @param \PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode|\PHPStan\PhpDocParser\Ast\Type\UnionTypeNode $typeNode
     */
    private function fixCompoundTypeNode($typeNode): TypeNode
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
