<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\StaticTypeMapper\PhpDocParser;

use Override;
use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedGenericObjectType;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Extension;
use function count;

/**
 * Allow the use of `\SilverStripe\Core\Extensible&\SilverStripe\Core\Extension` which would normally resolve to NEVER.
 *
 * @implements PhpDocTypeMapperInterface<GenericTypeNode>
 */
final readonly class ExtensionTypeMapper implements PhpDocTypeMapperInterface
{
    public function __construct(
        private TypeNodeResolver $typeNodeResolver
    ) {
    }

    #[Override]
    public function getNodeType(): string
    {
        return GenericTypeNode::class;
    }

    /**
     * @param GenericTypeNode $typeNode
     */
    #[Override]
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        $genericTypes = [];

        // If the type is a candidate for `\SilverStripe\Core\Extensible&\SilverStripe\Core\Extension` attempt to resolve it, otherwise fallback
        foreach ($typeNode->genericTypes as $genericTypeNode) {
            if ($genericTypeNode instanceof IntersectionTypeNode) {
                $genericTypes[] = $this->resolveIntersectionTypeNode($genericTypeNode, $nameScope);
                continue;
            }

            if ($genericTypeNode instanceof UnionTypeNode) {
                $genericTypes[] = $this->resolveUnionTypeNode($genericTypeNode, $nameScope);
                continue;
            }

            return $this->typeNodeResolver->resolve($typeNode, $nameScope);
        }

        return new FullyQualifiedGenericObjectType($nameScope->resolveStringName($typeNode->type->name), $genericTypes);
    }

    private function resolveUnionTypeNode(UnionTypeNode $typeNode, NameScope $nameScope): Type
    {
        $types = [];

        foreach ($typeNode->types as $genericTypeNode) {
            if ($genericTypeNode instanceof IntersectionTypeNode) {
                $type = $this->resolveIntersectionTypeNode($genericTypeNode, $nameScope);

                if (!$type instanceof IntersectionType) {
                    return $this->typeNodeResolver->resolve($genericTypeNode, $nameScope);
                }

                $types[] = $type;

                continue;
            }

            // We are not dealing with `\SilverStripe\Core\Extensible&\SilverStripe\Core\Extension` so fallback
            return $this->typeNodeResolver->resolve($typeNode, $nameScope);
        }

        return new UnionType($types);
    }

    private function resolveIntersectionTypeNode(IntersectionTypeNode $typeNode, NameScope $nameScope): Type
    {
        // Limit the amount of types, there should be only two
        if (count($typeNode->types) !== 2) {
            return $this->typeNodeResolver->resolve($typeNode, $nameScope);
        }

        $extensibleType = $this->typeNodeResolver->resolve($typeNode->types[0], $nameScope);
        $extensionType = $this->typeNodeResolver->resolve($typeNode->types[1], $nameScope);

        if ($this->shouldSkipExtensibleType($extensibleType)) {
            return $this->typeNodeResolver->resolve($typeNode, $nameScope);
        }

        if ($this->shouldSkipExtensionType($extensionType)) {
            return $this->typeNodeResolver->resolve($typeNode, $nameScope);
        }

        return new IntersectionType([$extensibleType, $extensionType]);
    }

    private function shouldSkipExtensibleType(Type $type): bool
    {
        if (!$type instanceof ObjectType) {
            return true;
        }

        $classReflection = $type->getClassReflection();

        if (!$classReflection instanceof ClassReflection) {
            return true;
        }

        return !$classReflection->hasTraitUse(Extensible::class);
    }

    private function shouldSkipExtensionType(Type $type): bool
    {
        if (!$type instanceof StaticType && !$type instanceof ObjectType) {
            return true;
        }

        $classReflection = $type->getClassReflection();

        if (!$classReflection instanceof ClassReflection) {
            return true;
        }

        return !$classReflection->isSubclassOf(Extension::class);
    }
}
