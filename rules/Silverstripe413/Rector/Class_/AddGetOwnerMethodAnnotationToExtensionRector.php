<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\Generic\GenericObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\AddGetOwnerMethodAnnotationToExtensionRectorTest
 */
final class AddGetOwnerMethodAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
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
