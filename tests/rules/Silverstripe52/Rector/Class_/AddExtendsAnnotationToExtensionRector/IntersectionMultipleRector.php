<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo;
use Override;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\StaticType;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\Extension;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @internal
 *
 * {@see \Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector} does not receive the proper owners in this test environment.
 *
 * The intention of this class is to mock the return of the aforementioned class to get some usable types to test.
 */
final class IntersectionMultipleRector extends AbstractAddAnnotationsToExtensionRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    #[Override]
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $originalType = new UnionType([
            new IntersectionType([new FullyQualifiedObjectType(OwnerMockOne::class), new StaticType($classReflection)]),
            new IntersectionType([new FullyQualifiedObjectType(OwnerMockTwo::class), new StaticType($classReflection)]),
        ]);

        $types = [];

        foreach ($originalType->getTypes() as $type) {
            if (!$type instanceof IntersectionType) {
                continue;
            }

            $types[] = new IntersectionTypeNode([
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type->getTypes()[0]),
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type->getTypes()[1]),
            ]);
        }

        $genericTypeNode = new GenericTypeNode(
            $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(new FullyQualifiedObjectType(Extension::class)), // @phpstan-ignore-line
            [new UnionTypeNode($types)]
        );

        return [
            new ExtendsTagValueNode(
                $genericTypeNode,
                ''
            ),
        ];
    }
}
