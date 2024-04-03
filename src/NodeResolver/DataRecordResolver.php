<?php

declare(strict_types=1);

namespace SilverstripeRector\NodeResolver;

use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Parser\RectorParser;
use function dirname;
use function glob;
use function str_replace;
use const BASE_PATH;

final readonly class DataRecordResolver
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private RectorParser $rectorParser,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * Resolve a dataRecord from the controller's class name.
     */
    public function resolveFullyQualifiedDataRecordClassNameFromControllerClassName(string $class): ?string
    {
        $controllerShortName = $this->nodeNameResolver->getShortName($class);

        // Skip all of this if we are dealing with PageController
        if ($controllerShortName === 'PageController') {
            return 'Page';
        }

        $classReflection = $this->reflectionProvider->getClass($class);
        $fileName = $classReflection->getFileName();

        if ($fileName === null) {
            return null;
        }

        $dataRecordShortName = str_replace('Controller', '', $controllerShortName);
        $controllerDirectory = dirname($fileName);

        // traverse up, until first class appears
        $dataRecordFiles = [];

        while ($dataRecordFiles === [] && $controllerDirectory !== BASE_PATH) {
            $dataRecordFiles = (array) glob($controllerDirectory . '/**/' . $dataRecordShortName . '.php');
            $controllerDirectory = dirname($controllerDirectory);
        }

        /** @var string[] $dataRecordFiles */
        if ($dataRecordFiles === []) {
            return null;
        }

        return $this->resolveClassNameFromFilePath($dataRecordFiles[0]);
    }

    private function resolveClassNameFromFilePath(string $filePath): ?string
    {
        $nodes = $this->rectorParser->parseFile($filePath);

        $classLike = $this->betterNodeFinder->findFirstNonAnonymousClass($nodes);

        if (!$classLike instanceof ClassLike) {
            return null;
        }

        return $this->nodeNameResolver->getName($classLike);
    }
}
