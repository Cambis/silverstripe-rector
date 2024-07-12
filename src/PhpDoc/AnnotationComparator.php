<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc;

use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Webmozart\Assert\Assert;
use function is_a;

final class AnnotationComparator
{
    /**
     * @readonly
     */
    private array $annotationComparators;
    public function __construct(
        array $annotationComparators
    ) {
        /**
         * @var AnnotationComparatorInterface<PhpDocTagValueNode>[]
         */
        $this->annotationComparators = $annotationComparators;
        Assert::notEmpty($annotationComparators);
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
