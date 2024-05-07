<?php

declare(strict_types=1);

namespace SilverstripeRector\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use SilverstripeRector\DocBlock\DocBlockHelper;
use SilverstripeRector\NodeAnalyzer\SilverstripeAnalyzer;
use SilverstripeRector\NodeFactory\MissingAnnotationsFactory;

abstract class AbstractAddAnnotationsRector extends AbstractRector
{
    /**
     * @readonly
     */
    protected ClassAnalyzer $classAnalyzer;
    /**
     * @readonly
     */
    protected SilverstripeAnalyzer $silverstripeAnalyzer;
    /**
     * @readonly
     */
    protected DocBlockHelper $docBlockHelper;
    /**
     * @readonly
     */
    protected DocBlockUpdater $docBlockUpdater;
    /**
     * @readonly
     */
    protected MissingAnnotationsFactory $missingAnnotationsFactory;
    /**
     * @readonly
     */
    protected PhpDocInfoFactory $phpDocInfoFactory;
    /**
     * @readonly
     */
    protected ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    protected StaticTypeMapper $staticTypeMapper;
    public function __construct(ClassAnalyzer $classAnalyzer, SilverstripeAnalyzer $silverstripeAnalyzer, DocBlockHelper $docBlockHelper, DocBlockUpdater $docBlockUpdater, MissingAnnotationsFactory $missingAnnotationsFactory, PhpDocInfoFactory $phpDocInfoFactory, ReflectionProvider $reflectionProvider, StaticTypeMapper $staticTypeMapper)
    {
        $this->classAnalyzer = $classAnalyzer;
        $this->silverstripeAnalyzer = $silverstripeAnalyzer;
        $this->docBlockHelper = $docBlockHelper;
        $this->docBlockUpdater = $docBlockUpdater;
        $this->missingAnnotationsFactory = $missingAnnotationsFactory;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->reflectionProvider = $reflectionProvider;
        $this->staticTypeMapper = $staticTypeMapper;
    }

    /**
     * @return array<class-string<Node>>
     */
    final public function getNodeTypes(): array
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
            $this->addDocTagValueNode($phpDocInfo, $newDocTagValueNode);
        }
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return $node;
    }

    protected function addDocTagValueNode(PhpDocInfo $phpDocInfo, PhpDocTagValueNode $phpDocTagValueNode): void
    {
        $phpDocInfo->addTagValueNode($phpDocTagValueNode);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    abstract protected function getNewDocTagValueNodes(Node $node): array;

    abstract protected function shouldSkipClass(Class_ $class): bool;
}
