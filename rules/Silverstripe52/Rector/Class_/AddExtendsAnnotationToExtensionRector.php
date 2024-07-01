<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\StaticType;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\Extension;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_values;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\AddExtendsAnnotationToExtensionRectorTest
 */
final class AddExtendsAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @extends Extension<(Foo & static)>
 */
class FooExtension extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
            ,
            [
                self::SET_TYPE_STYLE => self::SET_INTERSECTION,
            ]
        )]);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $parentClass = $classReflection->getParentClass();
        $extensionClassConst = ($parentClass instanceof ClassReflection) ? $parentClass->getName() : Extension::class;
        $originalType = array_values($this->silverstripeAnalyzer->extractMethodTypesFromOwners($classConst, $this->isIntersection()))[0];
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

            foreach ($originalType->getTypes() as $type) {
                if ($type instanceof IntersectionType) {
                    $types[] = new IntersectionTypeNode([
                        $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type->getTypes()[0]),
                        $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type->getTypes()[1]),
                    ]);

                    continue;
                }

                $types[] = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type);
            }

            $genericTypes[] = new UnionTypeNode($types);
        }
        if ($genericTypes === []) {
            return [];
        }
        $genericTypeNode = new GenericTypeNode(
            $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(new FullyQualifiedObjectType($extensionClassConst)), // @phpstan-ignore-line
            $genericTypes
        );
        return [
            new ExtendsTagValueNode(
                $genericTypeNode,
                ''
            ),
        ];
    }
}
