<?php

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use SilverStripe\Core\Extensible;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\AddExtensionMixinAnnotationsToExtensibleRectorTest
 */
final class AddExtensionMixinAnnotationsToExtensibleRector extends AbstractAddAnnotationsRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $extensions = [
        Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @mixin Bar
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $extensions = [
        Bar::class,
    ];
}
CODE_SAMPLE
        ),
        ]);
    }

    protected function addDocTagValueNode(PhpDocInfo $phpDocInfo, PhpDocTagValueNode $phpDocTagValueNode): void
    {
        $phpDocInfo->addPhpDocTagNode(new PhpDocTagNode('@mixin', $phpDocTagValueNode));
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $mixinProperties = $this->configurableAnalyzer->extractMixinTypesFromExtensions($classConst);

        return [
            ...$this->docBlockHelper->convertTypesToMixinTagValueNodes(
                $mixinProperties
            ),
        ];
    }

    protected function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $className = $this->nodeNameResolver->getName($class);

        if (is_null($className)) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return !$classReflection->hasTraitUse(Extensible::class);
    }
}
