<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToExtensionRector;
use InvalidArgumentException;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\VersionBonding\Contract\ComposerPackageConstraintInterface;
use Rector\VersionBonding\ValueObject\ComposerPackageConstraint;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;
use function is_bool;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\AddExtendsAnnotationToExtensionRectorTest
 */
final class AddExtendsAnnotationToExtensionRector extends AbstractAddAnnotationsToExtensionRector implements ConfigurableRectorInterface, ComposerPackageConstraintInterface
{
    /**
     * If true, allow the use of subclasses of `SilverStripe\Core\Extension`. If false, only `SilverStripe\Core\Extension` will be used in the `@extends` annotation.
     */
    private bool $allowSubclasses = true;

    public function provideComposerPackageConstraint(): ComposerPackageConstraint
    {
        return new ComposerPackageConstraint('silverstripe/framework', '>=5.2');
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class FooExtension extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @extends Extension<(Foo & static)>
 */
class FooExtension extends \SilverStripe\Core\Extension
{
}
CODE_SAMPLE
        )]);
    }

    public function configure(array $configuration): void
    {
        $allowSubclasses = $configuration['allowSubclasses'] ?? true;

        if (!is_bool($allowSubclasses)) {
            throw new InvalidArgumentException('The "allowSubclasses" configuration option must be a boolean.');
        }

        $this->allowSubclasses = $allowSubclasses;
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    #[Override]
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);

        $types = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty(
            $classReflection,
            '__silverstan_owners'
        );

        if ($types === []) {
            return [];
        }

        if (!array_key_exists('__getOwners', $types)) {
            return [];
        }

        $type = $types['__getOwners'];

        if (!$type instanceof GenericObjectType) {
            return [];
        }

        if (!$this->allowSubclasses) {
            $type = new GenericObjectType('SilverStripe\Core\Extension', $type->getTypes());
        }

        $type = $this->phpDocHelper->transformObjectTypeIntoFullyQualifiedObjectType($type);
        $genericTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($type);

        if (!$genericTypeNode instanceof GenericTypeNode) {
            return [];
        }

        return [
            new ExtendsTagValueNode(
                $genericTypeNode,
                ''
            ),
        ];
    }
}
