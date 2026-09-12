<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node\Name\FullyQualified;
use function is_string;

final class NewFactory
{
    /**
     * @readonly
     */
    private BuilderFactory $builderFactory;
    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    /**
     * @param mixed $class
     * @param mixed[] $args
     * @param bool $useCreate `Injectable::create()` rather than `new Injectable()`.
     * @return \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\StaticCall
     */
    public function createInjectable($class, array $args, bool $useCreate)
    {
        if (is_string($class)) {
            $class = new FullyQualified($class);
        }

        if ($useCreate) {
            return $this->builderFactory->staticCall(
                $class,
                'create',
                $args
            );
        }

        return $this->builderFactory->new($class, $args);
    }
}
