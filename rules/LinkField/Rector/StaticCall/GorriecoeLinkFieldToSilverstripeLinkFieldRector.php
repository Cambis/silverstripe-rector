<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\StaticCall;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\SilverstripeRector\NodeFactory\NewFactory;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_filter;
use function array_is_list;
use function array_keys;
use function is_array;
use function is_bool;
use function is_string;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/02_gorriecoe-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\GorriecoeLinkFieldToSilverstripeLinkFieldRectorTest
 */
final class GorriecoeLinkFieldToSilverstripeLinkFieldRector extends AbstractRector implements RelatedConfigInterface
{
    /**
     * @var array<string, string>
     */
    private const LINK_TYPES = [
        'URL' => 'SilverStripe\LinkField\Models\ExternalLink',
        'Email' => 'SilverStripe\LinkField\Models\EmailLink',
        'Phone' => 'SilverStripe\LinkField\Models\PhoneLink',
        'File' => 'SilverStripe\LinkField\Models\FileLink',
        'SiteTree' => 'SilverStripe\LinkField\Models\SiteTreeLink',
    ];

    /**
     * @var string
     */
    private const LINK_FIELD_CLASS = 'SilverStripe\LinkField\Form\LinkField';

    /**
     * @var string
     */
    private const MULTI_LINK_FIELD_CLASS = 'SilverStripe\LinkField\Form\MultiLinkField';

    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly NewFactory $newFactory,
        private readonly ConfigurationResolver $configurationResolver,
        private readonly ValueResolver $valueResolver
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `gorriecoe\LinkField\LinkField` to `SilverStripe\LinkField\Form\LinkField`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\gorriecoe\LinkField\LinkField::create('Link', 'Link', $this, ['types' => ['SiteTree']]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\LinkField\Form\LinkField::create('Link', 'Link')
    ->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class]);
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [New_::class, StaticCall::class];
    }

    /**
     * @param New_|StaticCall $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (!$this->isName($node->class, 'gorriecoe\LinkField\LinkField')) {
            return null;
        }

        if ($node instanceof StaticCall && !$this->isName($node->name, SilverstripeConstants::METHOD_CREATE)) {
            return null;
        }

        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        $formFieldClass = $this->resolveFormFieldClass($node);
        $args = $node->getArgs();

        $node = $this->newFactory->createInjectable(
            $formFieldClass,
            array_filter([$args[0] ?? null, $args[1]]),
            $node instanceof StaticCall
        );

        $linkConfigArg = $args[3] ?? null;

        if (!$linkConfigArg instanceof Arg) {
            return $node;
        }

        $linkConfig = $this->getLinkConfig($linkConfigArg);

        if ($linkConfig === []) {
            return $node;
        }

        $legacyAllowedTypes = $linkConfig['types'] ?? [];

        // Migrate types to LinkField::setAllowedTypes()
        if (is_array($legacyAllowedTypes) && $legacyAllowedTypes !== []) {

            // Turn into list if it is not, new config is a list
            if (!array_is_list($legacyAllowedTypes)) {
                $legacyAllowedTypes = array_keys($legacyAllowedTypes);
            }

            $allowedTypes = [];

            foreach ($legacyAllowedTypes as $allowedType) {
                if (!isset(self::LINK_TYPES[$allowedType])) {
                    continue;
                }

                $allowedTypes[] = $this->nodeFactory->createClassConstReference(self::LINK_TYPES[$allowedType]);
            }

            $node = $this->nodeFactory->createMethodCall(
                $node,
                'setAllowedTypes',
                [$this->nodeFactory->createArray($allowedTypes)]
            );
        }

        // Migrate title_display to LinkField::setExcludeLinkTextField()
        if (isset($linkConfig['title_display'])) {
            $titleDisplay = $linkConfig['title_display'];

            if (is_bool($titleDisplay)) {
                $node = $this->nodeFactory->createMethodCall(
                    $node,
                    'setExcludeLinkTextField',
                    [$titleDisplay]
                );
            }
        }

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * Resolve the parent.
     */
    private function resolveParentClass(Type $type): ?ClassReflection
    {
        foreach ($type->getObjectClassReflections() as $classReflection) {
            if (!$classReflection->is('SilverStripe\ORM\DataObject')) {
                continue;
            }

            return $classReflection;
        }

        return null;
    }

    /**
     * Resolve the class of form field to use. Single relations should use `LinkField`, while multi relations should use `MultiLinkField`.
     */
    private function resolveFormFieldClass(New_|StaticCall $node): string
    {
        $name = $node->getArgs()[0] ?? null;

        if (!$name instanceof Arg) {
            return self::LINK_FIELD_CLASS;
        }

        $parent = $node->getArgs()[2] ?? null;

        if (!$parent instanceof Arg) {
            return self::LINK_FIELD_CLASS;
        }

        $nameValue = $this->valueResolver->getValue($name);
        $parentClass = $this->resolveParentClass($this->getType($parent->value));

        // Couldn't resolve name, fallback
        if (!is_string($nameValue)) {
            return self::LINK_FIELD_CLASS;
        }

        // Couldn't resolve parent, fallback
        if (!$parentClass instanceof ClassReflection) {
            return self::LINK_FIELD_CLASS;
        }

        $hasMany = $this->configurationResolver->get($parentClass->getName(), SilverstripeConstants::PROPERTY_HAS_MANY);

        // Check if link is a has many
        if (is_array($hasMany) && isset($hasMany[$nameValue])) {
            return self::MULTI_LINK_FIELD_CLASS;
        }

        $manyMany = $this->configurationResolver->get($parentClass->getName(), SilverstripeConstants::PROPERTY_MANY_MANY);

        // Check if link is a many many
        if (is_array($manyMany) && isset($manyMany[$nameValue])) {
            return self::MULTI_LINK_FIELD_CLASS;
        }

        // Fallback
        return self::LINK_FIELD_CLASS;
    }

    /**
     * @return mixed[]
     */
    private function getLinkConfig(Arg $linkConfigArg): array
    {
        // Get the value of linkConfig
        $linkConfig = $this->valueResolver->getValue($linkConfigArg);

        if (!is_array($linkConfig)) {
            return [];
        }

        return $linkConfig;
    }
}
