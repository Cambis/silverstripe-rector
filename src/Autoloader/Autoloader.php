<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Autoloader;

use Cambis\Silverstan\ClassManifest\ClassManifest;
use Throwable;
use function file_exists;
use function spl_autoload_register;

final readonly class Autoloader
{
    public function __construct(
        private ClassManifest $classManifest
    ) {
    }

    public function register(): void
    {
        /** @phpstan-ignore-next-line */
        spl_autoload_register($this->autoload(...));
    }

    private function autoload(string $className): void
    {
        /** @var class-string $className */
        if (!$this->classManifest->hasClass($className)) {
            return;
        }

        // Safety check
        if (!file_exists($this->classManifest->getClassPath($className))) {
            return;
        }

        // require_once does not seem to load classes as expected, use require instead
        // and wrap in it a try catch just incase
        try {
            require $this->classManifest->getClassPath($className);
        } catch (Throwable) {
        }
    }
}
