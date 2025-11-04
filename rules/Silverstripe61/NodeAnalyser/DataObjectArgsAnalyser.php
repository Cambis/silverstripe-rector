<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe61\NodeAnalyser;

use PhpParser\Node\Arg;
use PHPStan\Reflection\ReflectionProvider;
use Rector\PhpParser\Node\Value\ValueResolver;
use function is_bool;
use function is_numeric;
use function is_string;

final class DataObjectArgsAnalyser
{
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;
    public function __construct(ReflectionProvider $reflectionProvider, ValueResolver $valueResolver)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->valueResolver = $valueResolver;
    }

    /**
     * Get the class name value from `DataObject::get_by_id()` or `DataObject::get_one()`.
     *
     * @param Arg[] $args
     */
    public function getDataClassNameFromArgs(array $args): ?string
    {
        $classNameArg = $args[0] ?? null;

        if (!$classNameArg instanceof Arg) {
            return null;
        }

        $classNameArgValue = $this->valueResolver->getValue($classNameArg);

        if (!is_string($classNameArgValue)) {
            return null;
        }

        if (!$this->reflectionProvider->hasClass($classNameArgValue)) {
            return null;
        }

        return $classNameArgValue;
    }

    /**
     * Get the `id` argument from `DataObject::get_by_id()`.
     *
     * @param Arg[] $args
     */
    public function getIdFromArgs(array $args): ?Arg
    {
        foreach ($args as $arg) {
            if (!is_numeric($this->valueResolver->getValue($arg))) {
                continue;
            }

            return $arg;
        }

        return null;
    }

    /**
     * Get the `cached` argument from `DataObject::get_by_id()` or `DataObject::get_one()`.
     *
     * @param Arg[] $args
     */
    public function getIsCachedFromArgs(array $args): ?Arg
    {
        foreach ($args as $arg) {
            if (!is_bool($this->valueResolver->getValue($arg))) {
                continue;
            }

            return $arg;
        }

        return null;
    }
}
