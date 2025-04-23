<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\Class_;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\SilverstripeRector\LinkField\NodeManipulator\PropertyManipulator;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\NodeFactory\PropertyFactory;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function in_array;
use function is_array;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/01_linkable-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\GorriecoeLinkToSilverstripeLinkRectorTest
 */
final class SheadawsonLinkableToSilverstripeLinkRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    /**
     * @var string
     */
    private const LEGACY_LINK_CLASS = 'Sheadawson\Linkable\Models\Link';

    public function __construct(
        private readonly ClassAnalyser $classAnalyser,
        private readonly ConfigurationResolver $configurationResolver,
        private readonly PropertyFactory $propertyFactory,
        private readonly PropertyManipulator $propertyManipulator
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `Sheadawson\Linkable\Models\Link` configuration to `SilverStripe\LinkField\Models\Link` configuration.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \Sheadawson\Linkable\Models\Link::class,
    ];

    private static array $many_many = [
        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class,
    ];

    private static array $many_many_extraFields = [
        'ManyManyLinks' => [
            'Sort' => 'Int',
        ],
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
        'ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];

    private static array $owns = [
        'HasOneLink',
        'HasManyLinks',
        'ManyManyLinks',
    ];
}
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (!$this->classAnalyser->isDataObject($node)) {
            return null;
        }

        // Variable to track if any actual change has been made
        $hasChanged = false;

        $hasOne = $this->propertyFactory->findConfigurationProperty($node, 'has_one');

        // Migrate has_one configuration
        if ($hasOne instanceof Property) {
            $node = $this->propertyManipulator->refactorHasOne($node, $hasOne, self::LEGACY_LINK_CLASS, $this->shouldAddMemberToOwns($node), $hasChanged);
        }

        $manyMany = $this->propertyFactory->findConfigurationProperty($node, 'many_many');

        // Migrate many_many to has_many
        if ($manyMany instanceof Property) {
            $node = $this->propertyManipulator->refactorManyMany($node, $manyMany, self::LEGACY_LINK_CLASS, $hasChanged);
        }

        $hasMany = $this->propertyFactory->findConfigurationProperty($node, 'has_many');

        // Migrate has_many configuration
        if ($hasMany instanceof Property) {
            $node = $this->propertyManipulator->refactorHasMany($node, $hasMany, self::LEGACY_LINK_CLASS, $this->shouldAddMemberToOwns($node), $hasChanged);
        }

        // No change? Return null
        if (!$hasChanged) {
            return null;
        }

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * Return false if the class is in `SilverStripe\LinkField\Tasks\LinkableMigrationTask::$classes_that_are_not_link_owners`.
     */
    private function shouldAddMemberToOwns(Class_ $class): bool
    {
        $notOwners = $this->configurationResolver->get('SilverStripe\LinkField\Tasks\LinkableMigrationTask', 'classes_that_are_not_link_owners');

        if (!is_array($notOwners) || $notOwners === []) {
            return true;
        }

        return !in_array($this->getName($class), $notOwners, true);
    }
}
