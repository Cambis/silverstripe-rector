<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\Class_;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\SilverstripeRector\LinkField\NodeManipulator\PropertyManipulator;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\NodeFactory\PropertyFactory;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function in_array;
use function is_array;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/00_upgrading.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SilverstripeLinkLegacyRector\SilverstripeLinkLegacyRectorTest
 */
final class SilverstripeLinkLegacyRector extends AbstractRector implements RelatedConfigInterface
{
    /**
     * @readonly
     */
    private ClassAnalyser $classAnalyser;
    /**
     * @readonly
     */
    private ConfigurationResolver $configurationResolver;
    /**
     * @readonly
     */
    private PropertyFactory $propertyFactory;
    /**
     * @readonly
     */
    private PropertyManipulator $propertyManipulator;
    public function __construct(ClassAnalyser $classAnalyser, ConfigurationResolver $configurationResolver, PropertyFactory $propertyFactory, PropertyManipulator $propertyManipulator)
    {
        $this->classAnalyser = $classAnalyser;
        $this->configurationResolver = $configurationResolver;
        $this->propertyFactory = $propertyFactory;
        $this->propertyManipulator = $propertyManipulator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate legacy `SilverStripe\LinkField\Model\Link` configuration to `SilverStripe\LinkField\Models\Link` v4 configuration.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class,
    ];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];

    private static array $owns = [
        'HasOneLink',
        'HasManyLinks',
    ];
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->classAnalyser->isDataObject($node)) {
            return null;
        }
        // Variable to track if any actual change has been made
        $hasChanged = false;
        $hasOne = $this->propertyFactory->findConfigurationProperty($node, SilverstripeConstants::PROPERTY_HAS_ONE);
        // Migrate has_one configuration
        if ($hasOne instanceof Property) {
            $node = $this->propertyManipulator->refactorHasOne($node, $hasOne, 'SilverStripe\LinkField\Models\Link', $this->shouldAddMemberToOwns($node), $hasChanged);
        }
        $hasMany = $this->propertyFactory->findConfigurationProperty($node, SilverstripeConstants::PROPERTY_HAS_MANY);
        // Migrate has_many configuration
        if ($hasMany instanceof Property) {
            $node = $this->propertyManipulator->refactorHasMany($node, $hasMany, 'SilverStripe\LinkField\Models\Link', $this->shouldAddMemberToOwns($node), $hasChanged);
        }
        if (!$hasChanged) {
            return null;
        }
        return $node;
    }

    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * Return false if the class is in `SilverStripe\LinkField\Tasks\LinkFieldMigrationTask::$classes_that_are_not_link_owners`.
     */
    private function shouldAddMemberToOwns(Class_ $class): bool
    {
        $notOwners = $this->configurationResolver->get('SilverStripe\LinkField\Tasks\LinkFieldMigrationTask', 'classes_that_are_not_link_owners');

        if (!is_array($notOwners) || $notOwners === []) {
            return true;
        }

        return !in_array($this->getName($class), $notOwners, true);
    }
}
