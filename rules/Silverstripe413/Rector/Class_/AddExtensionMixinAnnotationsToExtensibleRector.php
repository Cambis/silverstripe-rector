<?php

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use SilverStripe\Core\Extensible;
use SilverstripeRector\DocBlock\DocBlockHelper;
use SilverstripeRector\NodeAnalyzer\ConfigurableAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\AddExtensionMixinAnnotationsToExtensibleRectorTest
 */
final class AddExtensionMixinAnnotationsToExtensibleRector extends AbstractRector
{
    public function __construct(
        private readonly ConfigurableAnalyzer $configurableAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly DocBlockHelper $docBlockHelper
    ) {
    }

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

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();

        $mixinProperties = $this->configurableAnalyzer->extractMixinTypesFromExtensions($classConst);
        $newDocTagValueNodes = [
            ...$this->docBlockHelper->convertTypesToMixinTagValueNodes(
                $mixinProperties
            ),
        ];

        if ($newDocTagValueNodes === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $phpDocInfo->getPhpDocNode();

        $newDocTagValueNodes = $this->docBlockHelper->filterOutExistingAnnotations(
            $node,
            $phpDocInfo,
            $newDocTagValueNodes
        );

        if ($newDocTagValueNodes === []) {
            return null;
        }

        foreach ($newDocTagValueNodes as $newDocTagValueNode) {
            $phpDocInfo->addPhpDocTagNode(new PhpDocTagNode('@mixin', $newDocTagValueNode));
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
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
