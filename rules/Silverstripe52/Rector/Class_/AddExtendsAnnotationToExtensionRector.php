<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;

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
        $types = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty(
            $classReflection,
            '__silverstan_owners'
        );
        if ($types === []) {
            return [];
        }
        if (!array_key_exists('__getOwners', $types)) {
            return [];
        }
        $type = $types['__getOwners'];
        if (!$type instanceof GenericObjectType) {
            return [];
        }
        $type = $this->phpDocHelper->transformObjectTypeIntoFullyQualifiedObjectType($type);
        $genericTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type);
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
