<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use SilverstripeRector\DocBlock\DocBlockHelper;
use SilverstripeRector\NodeAnalyzer\ConfigurableAnalyzer;

abstract class AbstractAddAnnotationsRector extends AbstractRector
{
    public function __construct(
        protected readonly ClassAnalyzer $classAnalyzer,
        protected readonly ConfigurableAnalyzer $configurableAnalyzer,
        protected readonly DocBlockHelper $docBlockHelper,
        protected readonly DocBlockUpdater $docBlockUpdater,
        protected readonly PhpDocInfoFactory $phpDocInfoFactory,
        protected readonly ReflectionProvider $reflectionProvider,
    ) {
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

        $newDocTagValueNodes = $this->getNewDocTagValueNodes($node);

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
            $this->addTagValueNode($phpDocInfo, $newDocTagValueNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    protected function addTagValueNode(PhpDocInfo $phpDocInfo, PhpDocTagValueNode $phpDocTagValueNode): void
    {
        $phpDocInfo->addTagValueNode($phpDocTagValueNode);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    abstract protected function getNewDocTagValueNodes(Node $node): array;

    abstract protected function shouldSkipClass(Class_ $class): bool;
}
