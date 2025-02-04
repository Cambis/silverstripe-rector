<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationUpdater;

use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use function array_filter;

final readonly class AnnotationUpdater
{
    public function __construct(
        private AnnotationComparator $annotationComparator,
        private DocBlockUpdater $docBlockUpdater,
    ) {
    }

    /**
     * @param PhpDocTagValueNode[] $newDocTagValueNodes
     */
    public function updateExistingAnnotations(Node $node, PhpDocInfo $phpDocInfo, array $newDocTagValueNodes): void
    {
        // Create a copy of the current child nodes
        $unmodified = [...$phpDocInfo->getPhpDocNode()->children];

        // These will the new child nodes
        $modified = [...$unmodified];

        $hasChanged = false;

        // Loop over every existing node and update if needed
        foreach ($newDocTagValueNodes as $newNode) {
            foreach ($unmodified as $key => $phpDocTagNode) {
                if (!$phpDocTagNode instanceof PhpDocTagNode) {
                    continue;
                }

                if (!$modified[$key] instanceof PhpDocTagNode) {
                    continue;
                }

                $originalNode = $phpDocTagNode->value;

                if ($originalNode instanceof InvalidTagValueNode) {
                    continue;
                }

                if (!$this->annotationComparator->areTagValueNodeNamesEqual($originalNode, $newNode, $node)) {
                    continue;
                }

                if (!$this->annotationComparator->shouldUpdateTagValueNode($originalNode, $newNode, $node)) {
                    continue;
                }

                $modified[$key]->value = $newNode;
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            $phpDocInfo->getPhpDocNode()->children = $modified;
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        }
    }

    /**
     * @param PhpDocTagValueNode[] $newDocTagValueNodes
     * @return PhpDocTagValueNode[]
     */
    public function filterOutExistingAnnotations(PhpDocInfo $phpDocInfo, array $newDocTagValueNodes, Node $node): array
    {
        $childrenMap = [...$phpDocInfo->getPhpDocNode()->children];

        return array_filter(
            $newDocTagValueNodes,
            function (PhpDocTagValueNode $newNode) use ($childrenMap, $node): bool {
                foreach ($childrenMap as $phpDocTagNode) {
                    if (!$phpDocTagNode instanceof PhpDocTagNode) {
                        continue;
                    }

                    $originalNode = $phpDocTagNode->value;

                    if ($originalNode instanceof InvalidTagValueNode) {
                        continue;
                    }

                    if (!$this->annotationComparator->areTagValueNodeNamesEqual($originalNode, $newNode, $node)) {
                        continue;
                    }

                    return false;
                }

                return true;
            }
        );
    }
}
