<?php

declare(strict_types=1);

namespace SilverstripeRector\NodeFactory;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use SilverstripeRector\TypeComparator\AnnotationComparator;
use function array_filter;

final class MissingAnnotationsFactory
{
    public function __construct(
        private readonly AnnotationComparator $annotationComparator,
    ) {
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
                foreach ($phpDocInfo->getPhpDocNode()->children as $key => $phpDocTagNode) {
                    if (!$phpDocTagNode instanceof PhpDocTagNode) {
                        continue;
                    }

                    $phpDocTagValueNode = $phpDocTagNode->value;

                    if (!$phpDocTagValueNode instanceof PhpDocTagValueNode) {
                        continue;
                    }

                    if ($this->annotationComparator->shouldSkipNewAnnotation($phpDocTagValueNode, $newDocTagValueNode, $node)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
