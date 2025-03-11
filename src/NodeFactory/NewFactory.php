<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeFactory;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use function is_string;

final readonly class NewFactory
{
    public function __construct(
        private BuilderFactory $builderFactory
    ) {
    }

    /**
     * @param Expr|Name|string $class
     * @param mixed[] $args
     * @param use $useCreate `Injectable::create()` rather than `new Injectable()`.
     */
    public function createInjectable(mixed $class, array $args, bool $useCreate): New_|StaticCall
    {
        if (is_string($class)) {
            $class = new FullyQualified($class);
        }

        if ($useCreate) {
            return $this->builderFactory->staticCall(
                $class,
                SilverstripeConstants::METHOD_CREATE,
                $args
            );
        }

        return $this->builderFactory->new($class, $args);
    }
}
