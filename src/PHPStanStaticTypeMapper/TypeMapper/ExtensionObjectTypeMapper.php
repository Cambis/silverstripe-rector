<?php

namespace Cambis\SilverstripeRector\PHPStanStaticTypeMapper\TypeMapper;

use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionGenericObjectType;
use Override;
use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

/**
 * @implements TypeMapperInterface<ExtensionGenericObjectType>
 */
final class ExtensionObjectTypeMapper implements TypeMapperInterface
{
    private StaticTypeMapper $staticTypeMapper;

    // To avoid circular dependency
    public function autowire(StaticTypeMapper $staticTypeMapper): void
    {
        $this->staticTypeMapper = $staticTypeMapper;
    }

    #[Override]
    public function getNodeClass(): string
    {
        return ExtensionGenericObjectType::class;
    }

    #[Override]
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        return new FullyQualified($type->getClassName());
    }

    /**
     * @param ExtensionGenericObjectType $type
     */
    #[Override]
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $originalType = $type->getTypes()[0];
        $genericTypes = [];

        if ($originalType instanceof StaticType) {
            $genericTypes[] = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($originalType);
        } elseif ($originalType instanceof IntersectionType) {
            $genericTypes[] = new IntersectionTypeNode([
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($originalType->getTypes()[0]),
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($originalType->getTypes()[1]),
            ]);
        } elseif ($originalType instanceof UnionType) {
            $types = [];

            foreach ($originalType->getTypes() as $memberType) {
                if ($memberType instanceof IntersectionType) {
                    $types[] = new IntersectionTypeNode([
                        $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($memberType->getTypes()[0]),
                        $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($memberType->getTypes()[1]),
                    ]);

                    continue;
                }

                $types[] = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($memberType);
            }

            $genericTypes[] = new UnionTypeNode($types);
        }

        return new GenericTypeNode(
            $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(new FullyQualifiedObjectType($type->getClassName())), // @phpstan-ignore-line,
            $genericTypes
        );
    }
}
