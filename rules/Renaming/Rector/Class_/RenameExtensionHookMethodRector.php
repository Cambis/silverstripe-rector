<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Renaming\Rector\Class_;

use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameExtensionHookMethod;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use InvalidArgumentException;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\RenameExtensionHookMethodRectorTest
 */
final class RenameExtensionHookMethodRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface, RelatedConfigInterface
{
    /**
     * @readonly
     */
    private ClassAnalyser $classAnalyser;
    /**
     * @var list<RenameExtensionHookMethod>
     */
    private array $hookMethodRenames = [];

    public function __construct(ClassAnalyser $classAnalyser)
    {
        $this->classAnalyser = $classAnalyser;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename an extension hook method definition if the extension is applied to a given class. This rector only applies to instances of `SilverStripe\Core\Extension`, for all other use cases use `RenameMethodRector` instead.', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
    protected function updateDoSomething(): void
    {
       // ...
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
    protected function updateDoSomethingElse(): void
    {
       // ...
    }
}
CODE_SAMPLE,
            [new RenameExtensionHookMethod('App\Model\Foo', 'updateDoSomething', 'updateDoSomethingElse')]
        ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
        if (!$scope->isInClass()) {
            return null;
        }
        if (!$this->classAnalyser->isExtension($node)) {
            return null;
        }
        $hasChanged = false;
        foreach ($node->getMethods() as $classMethod) {
            $methodName = $this->getName($classMethod->name);

            if ($methodName === null) {
                continue;
            }

            foreach ($this->hookMethodRenames as $hookMethodRename) {
                if ($this->shouldSkipRename($methodName, $hookMethodRename, $classMethod, $scope->getClassReflection(), $scope)) {
                    continue;
                }

                $classMethod->name = new Identifier($hookMethodRename->newMethodName);
                $hasChanged = true;

                continue 2;
            }
        }
        if (!$hasChanged) {
            return null;
        }
        return $node;
    }

    public function configure(array $configuration): void
    {
        foreach ($configuration as $value) {
            if (!$value instanceof RenameExtensionHookMethod) {
                throw new InvalidArgumentException(self::class . ' only accepts ' . RenameExtensionHookMethod::class);
            }
        }
        /** @var list<RenameExtensionHookMethod> $configuration */
        $this->hookMethodRenames = $configuration;
    }

    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    private function shouldSkipRename(string $methodName, RenameExtensionHookMethod $hookMethodRename, ClassMethod $classMethod, ClassReflection $classReflection, Scope $scope): bool
    {
        // Class method is private and therefore cannot be an extension hook, skip
        if ($classMethod->isPrivate()) {
            return true;
        }

        // Method already exists, skip
        if ($classReflection->hasMethod($hookMethodRename->newMethodName)) {
            return true;
        }

        // Method doesn't match rewrite, skip
        if (!$this->nodeNameResolver->isStringName($methodName, $hookMethodRename->oldMethodName)) {
            return true;
        }

        // Get the return type from the `getOwner` method
        $getOwnerMethodReflection = $classReflection->getMethod('getOwner', $scope);
        $type = $getOwnerMethodReflection->getVariants()[0]->getReturnType();

        return (new ObjectType($hookMethodRename->extensibleClassName))->isSuperTypeOf($type)->no();
    }
}
