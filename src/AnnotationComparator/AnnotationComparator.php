<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\AnnotationComparator;

use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use function is_a;

final readonly class AnnotationComparator
{
    public function __construct(
        /**
         * @var list<AnnotationComparatorInterface<PhpDocTagValueNode>>
         */
        private array $annotationComparators
    ) {
    }

    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        foreach ($this->annotationComparators as $annotationComparator) {
            if (!is_a($originalNode, $annotationComparator->getTagValueNodeClass(), true)) {
                continue;
            }

            if (!is_a($newNode, $annotationComparator->getTagValueNodeClass(), true)) {
                continue;
            }

            return $annotationComparator->areTagValueNodeNamesEqual($originalNode, $newNode, $node);
        }

        return false;
    }

    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool
    {
        foreach ($this->annotationComparators as $annotationComparator) {
            if (!is_a($originalNode, $annotationComparator->getTagValueNodeClass(), true)) {
                continue;
            }

            if (!is_a($newNode, $annotationComparator->getTagValueNodeClass(), true)) {
                continue;
            }

            return $annotationComparator->shouldUpdateTagValueNode($originalNode, $newNode, $node);
        }

        return false;
    }
}
