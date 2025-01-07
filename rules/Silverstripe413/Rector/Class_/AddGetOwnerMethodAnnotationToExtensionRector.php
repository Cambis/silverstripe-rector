<?php

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\Generic\GenericObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\AddGetOwnerMethodAnnotationToExtensionRectorTest
 */
final class AddGetOwnerMethodAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
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
 * @method (Foo & static) getOwner()
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

        if ($type instanceof GenericObjectType) {
            $type = $type->getTypes()[0];
        }

        return $this->phpDocHelper->convertTypesToMethodTagValueNodes(
            [
                SilverstripeConstants::METHOD_GET_OWNER => $type,
            ],
        );
    }
}
