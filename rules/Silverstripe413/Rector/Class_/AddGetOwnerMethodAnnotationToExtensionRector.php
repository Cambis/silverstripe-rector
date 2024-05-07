<?php

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\AddGetOwnerMethodAnnotationToExtensionRectorTest
 */
final class AddGetOwnerMethodAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
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
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $ownerProperties = $this->silverstripeAnalyzer->extractMethodTypesFromOwners($classConst, $this->isIntersection());
        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $ownerProperties
        );
    }
}
