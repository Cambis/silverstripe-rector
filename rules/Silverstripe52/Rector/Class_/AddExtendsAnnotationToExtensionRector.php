<?php

namespace SilverstripeRector\Silverstripe52\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use SilverStripe\Core\Extension;
use SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function count;

/**
 * @see \SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\AddExtendsAnnotationToExtensionRectorTest
 */
final class AddExtendsAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector
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
 * @extends \SilverStripe\Core\Extension<static>
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
        $typeNodes = $this->configurableAnalyzer->extractExtendsTypeNodesFromOwners($classConst);

        return [
            new ExtendsTagValueNode(
                new GenericTypeNode(
                    new FullyQualifiedIdentifierTypeNode(Extension::class),
                    count($typeNodes) > 1 ? [new UnionTypeNode($typeNodes)] : $typeNodes
                ),
                ''
            ),
        ];
    }

    protected function addDocTagValueNode(PhpDocInfo $phpDocInfo, PhpDocTagValueNode $phpDocTagValueNode): void
    {
        $phpDocInfo->addPhpDocTagNode(new PhpDocTagNode('@extends', $phpDocTagValueNode));
    }
}
