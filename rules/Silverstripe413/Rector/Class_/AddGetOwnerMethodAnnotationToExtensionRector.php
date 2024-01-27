<?php

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\AddGetOwnerMethodAnnotationToExtensionRectorTest
 */
final class AddGetOwnerMethodAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @method getOwner() $this
 */
class Foo extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
        ),
        ]);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $ownerProperties = $this->configurableAnalyzer->extractMethodTypesFromOwners($classConst);

        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $ownerProperties
        );
    }
}
