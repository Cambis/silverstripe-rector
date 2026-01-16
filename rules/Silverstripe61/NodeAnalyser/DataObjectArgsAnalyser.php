<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe61\NodeAnalyser;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class DataObjectArgsAnalyser
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
    ) {
    }

    /**
     * Get the class name value from `DataObject::get_by_id()` or `DataObject::get_one()`.
     */
    public function getDataClassName(StaticCall $staticCall): ?string
    {
        $candidates = [$staticCall->getArg('classOrID', 0), $staticCall->getArg('callerClass', 0)];

        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Arg) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($candidate->value);

            if ($type->isClassString()->no()) {
                // Skip if there is a string but not a class string
                if ($type->isString()->yes() && $staticCall->getArg('idOrCache', 1) instanceof Arg) {
                    return null;
                }

                continue;
            }

            if ((new ObjectType('SilverStripe\ORM\DataObject'))->isSuperTypeOf($type->getObjectTypeOrClassStringObjectType())->no()) {
                continue;
            }

            return $type->getObjectTypeOrClassStringObjectType()->getObjectClassNames()[0] ?? null;
        }

        return $this->nodeNameResolver->getName($staticCall->class);
    }

    /**
     * Get the `id` argument from `DataObject::get_by_id()`.
     */
    public function getId(StaticCall $staticCall): ?Arg
    {
        $candidates = [$staticCall->getArg('classOrID', 0), $staticCall->getArg('idOrCache', 1)];

        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Arg) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($candidate->value);

            if ($type->isInteger()->no()) {
                continue;
            }

            return $candidate;
        }

        return null;
    }

    /**
     * Get the `cached` argument from `DataObject::get_by_id()` or `DataObject::get_one()`.
     */
    public function getIsCached(StaticCall $staticCall): ?Arg
    {
        $candidates = [$staticCall->getArg('cache', 2)];

        if ($this->nodeNameResolver->isName($staticCall->name, 'get_by_id') && !$this->nodeNameResolver->isName($staticCall->class, 'SilverStripe\ORM\DataObject')) {
            $candidates = [$staticCall->getArg('idOrCache', 1)];
        }

        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Arg) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($candidate->value);

            if ($type->isBoolean()->no()) {
                continue;
            }

            return $candidate;
        }

        return null;
    }

    /**
     * Get the `filter` argument from `DataObject::get_one()`.
     */
    public function getFilter(StaticCall $staticCall): ?Arg
    {
        $candidates = [$staticCall->getArg('filter', 1)];

        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Arg) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($candidate->value);

            if ($type->isArray()->no() && $type->isString()->no()) {
                continue;
            }

            return $candidate;
        }

        return null;
    }

    /**
     * Get the `sort` argument from `DataObject::get_one()`.
     */
    public function getSort(StaticCall $staticCall): ?Arg
    {
        $candidates = [$staticCall->getArg('sort', 3)];

        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Arg) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($candidate->value);

            if ($type->isArray()->no() && $type->isString()->no() && $type->isNull()->no()) {
                continue;
            }

            return $candidate;
        }

        return null;
    }
}
