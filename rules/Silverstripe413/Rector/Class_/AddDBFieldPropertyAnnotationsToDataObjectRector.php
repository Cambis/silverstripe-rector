<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\AddDBFieldPropertyAnnotationsToDataObjectRectorTest
 */
final class AddDBFieldPropertyAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $db = [
        'Bar' => 'Varchar(255)',
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @property ?string $Bar
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $db = [
        'Bar' => 'Varchar(255)',
    ];
}
CODE_SAMPLE
        ),
        ]);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    #[Override]
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);

        $types = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty(
            $classReflection,
            SilverstripeConstants::PROPERTY_DB
        );

        foreach ($types as $name => $type) {
            $types[$name] = $this->getReadableType($classReflection, $type, $name);
        }

        return $this->phpDocHelper->convertTypesToPropertyTagValueNodes(
            $types
        );
    }

    private function getReadableType(ClassReflection $classReflection, Type $type, string $name): Type
    {
        // Safety checks...
        if ($type->isObject()->no()) {
            return $type;
        }

        if ($type->getObjectClassReflections() === []) {
            return $type;
        }

        $fieldClassReflection = $type->getObjectClassReflections()[0];

        if (!$fieldClassReflection->is('SilverStripe\ORM\FieldType\DBField')) {
            return $type;
        }

        // Check for custom `get<name>` function https://docs.silverstripe.org/en/5/developer_guides/model/data_types_and_casting/#overriding
        if ($classReflection->hasNativeMethod('get' . $name)) {
            return $classReflection->getNativeMethod('get' . $name)->getVariants()[0]->getReturnType();
        }

        // Attempt to return the type from the value property
        if ($fieldClassReflection->hasProperty('value')) {
            return $fieldClassReflection->getProperty('value', new OutOfClassScope())->getReadableType();
        }

        // Fallback, return the original type
        return $type;
    }
}
