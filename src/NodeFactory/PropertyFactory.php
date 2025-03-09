<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeFactory;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use Rector\NodeManipulator\ClassInsertManipulator;
use Rector\PhpParser\Node\NodeFactory;

final class PropertyFactory
{
    /**
     * @readonly
     */
    private ClassInsertManipulator $classInsertManipulator;
    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;
    public function __construct(ClassInsertManipulator $classInsertManipulator, NodeFactory $nodeFactory)
    {
        $this->classInsertManipulator = $classInsertManipulator;
        $this->nodeFactory = $nodeFactory;
    }

    public function findConfigurationProperty(Class_ $class, string $propertyName): ?Property
    {
        $property = $class->getProperty($propertyName);

        if (!$property instanceof Property) {
            return null;
        }

        if (!$property->isPrivate()) {
            return null;
        }

        if (!$property->isStatic()) {
            return null;
        }

        return $property;
    }

    public function createArrayConfigurationProperty(Class_ $class, string $propertyName): Property
    {
        $property = $this->findConfigurationProperty($class, $propertyName);

        if ($property instanceof Property) {
            return $property;
        }

        $property = $this->nodeFactory->createPrivatePropertyFromNameAndType(
            $propertyName,
            new ArrayType(new IntegerType(), new StringType())
        );

        $property->flags = Class_::MODIFIER_PRIVATE | Class_::MODIFIER_STATIC;

        $this->classInsertManipulator->addAsFirstMethod($class, $property);

        return $property;
    }
}
