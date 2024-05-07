<?php

declare(strict_types=1);

namespace SilverstripeRector\NodeFactory;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use SilverstripeRector\PhpDocManipulator\AnnotationUpdater;
use function array_filter;

final class MissingAnnotationsFactory
{
    /**
     * @readonly
     */
    private AnnotationUpdater $annotationUpdater;
    public function __construct(AnnotationUpdater $annotationUpdater)
    {
        $this->annotationUpdater = $annotationUpdater;
    }
    /**
     * @param PhpDocTagValueNode[] $newDocTagValueNodes
     * @return PhpDocTagValueNode[]
     */
    public function filterOutExistingAnnotations(Node $node, PhpDocInfo $phpDocInfo, array $newDocTagValueNodes): array
    {
        return array_filter(
            $newDocTagValueNodes,
            function (PhpDocTagValueNode $newDocTagValueNode) use ($node, $phpDocInfo): bool {
                foreach ($phpDocInfo->getPhpDocNode()->children as $phpDocTagNode) {
                    if (!$phpDocTagNode instanceof PhpDocTagNode) {
                        continue;
                    }

                    $phpDocTagValueNode = $phpDocTagNode->value;

                    if ($phpDocTagValueNode instanceof InvalidTagValueNode) {
                        continue;
                    }

                    if ($this->annotationUpdater->hasExistingAnnotation($phpDocTagValueNode, $newDocTagValueNode, $node)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
