<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\AddExtendsAnnotationToExtensionRectorTest
 */
final class AddExtendsAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
{
    #[Override]
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
    #[Override]
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $genericType = $this->configurationPropertyTypeResolver->resolveOwnerTypeFromOwners($classConst, $this->isIntersection());
        $genericTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($genericType);

        if (!$genericTypeNode instanceof GenericTypeNode) {
            return [];
        }

        return [
            new ExtendsTagValueNode(
                $genericTypeNode,
                ''
            ),
        ];
    }
}
