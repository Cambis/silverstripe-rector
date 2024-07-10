<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\PhpDoc\Contract;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;

/**
 * @template T of PhpDocTagValueNode
 */
interface AnnotationComparatorInterface
{
    /**
     * @return class-string<T>
     */
    public function getTagValueNodeClass(): string;

    /**
     * @param T $originalNode
     * @param T $newNode
     */
    public function areTagValueNodeNamesEqual(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool;

    /**
     * @param T $originalNode
     * @param T $newNode
     */
    public function shouldUpdateTagValueNode(PhpDocTagValueNode $originalNode, PhpDocTagValueNode $newNode, Node $node): bool;
}
