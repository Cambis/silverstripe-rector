<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Cambis\SilverstripeRector\DocBlock\DocBlockHelper;
use Cambis\SilverstripeRector\NodeAnalyzer\SilverstripeAnalyzer;
use Cambis\SilverstripeRector\NodeFactory\MissingAnnotationsFactory;
use Cambis\SilverstripeRector\NodeResolver\DataRecordResolver;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Exception\NotImplementedYetException;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;

abstract class AbstractAddAnnotationsRector extends AbstractRector
{
    /**
     * @var array<class-string<PhpDocTagValueNode>, string>
     */
    private const TAGS_TYPES_TO_NAMES = [
        ExtendsTagValueNode::class => '@extends',
        MethodTagValueNode::class => '@method',
        MixinTagValueNode::class => '@mixin',
        ImplementsTagValueNode::class => '@implements',
        PropertyTagValueNode::class => '@property',
        TemplateTagValueNode::class => '@template',
    ];

    public function __construct(
        protected readonly ClassAnalyzer $classAnalyzer,
        protected readonly DataRecordResolver $dataRecordResolver,
        protected readonly DocBlockHelper $docBlockHelper,
        protected readonly DocBlockUpdater $docBlockUpdater,
        protected readonly MissingAnnotationsFactory $missingAnnotationsFactory,
        protected readonly PhpDocInfoFactory $phpDocInfoFactory,
        protected readonly ReflectionProvider $reflectionProvider,
        protected readonly SilverstripeAnalyzer $silverstripeAnalyzer,
        protected readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    /**
     * @return array<class-string<Class_>>
     */
    #[Override]
    final public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $newDocTagValueNodes = $this->getNewDocTagValueNodes($node);

        if ($newDocTagValueNodes === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $newDocTagValueNodes = $this->missingAnnotationsFactory->filterOutExistingAnnotations(
            $node,
            $phpDocInfo,
            $newDocTagValueNodes
        );

        if ($newDocTagValueNodes === []) {
            return null;
        }

        foreach ($newDocTagValueNodes as $newDocTagValueNode) {
            $tagName = $this->resolveNameForPhpDocTagValueNode($newDocTagValueNode);
            $phpDocTagNode = new PhpDocTagNode($tagName, $newDocTagValueNode);
            $phpDocInfo->addPhpDocTagNode($phpDocTagNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    /**
     * @throws NotImplementedYetException
     */
    protected function resolveNameForPhpDocTagValueNode(PhpDocTagValueNode $phpDocTagValueNode): string
    {
        foreach (self::TAGS_TYPES_TO_NAMES as $tagValueNodeType => $name) {
            /** @var class-string<PhpDocTagValueNode> $tagValueNodeType */
            if ($phpDocTagValueNode instanceof $tagValueNodeType) {
                return $name;
            }
        }

        throw new NotImplementedYetException(__METHOD__ . ' not yet implemented for ' . $phpDocTagValueNode::class);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    abstract protected function getNewDocTagValueNodes(Class_ $class): array;

    abstract protected function shouldSkipClass(Class_ $class): bool;
}
