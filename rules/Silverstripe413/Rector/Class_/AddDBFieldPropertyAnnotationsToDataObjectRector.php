<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\AddDBFieldPropertyAnnotationsToDataObjectRectorTest
 */
final class AddDBFieldPropertyAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
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
 * @property string $Bar
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
     * @return PHPDocTagValueNode[]
     */
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();
        $dbProperties = $this->configurableAnalyzer->extractPropertyTypesFromDBFields($classConst);

        return $this->docBlockHelper->convertTypesToPropertyTagValueNodes(
            $dbProperties
        );
    }
}
