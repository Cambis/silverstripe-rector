<?php

namespace Cambis\SilverstripeRector\Tests\Rector;

use Cambis\SilverstripeRector\Rector\AbstractAPIAwareRector;
use Override;
use PhpParser\Node;
use PhpParser\NodeAbstract;
use PHPUnit\Framework\TestCase;
use Rector\Exception\ShouldNotHappenException;
use SilverStripe\Core\Config\ConfigLoader;
use SilverStripe\Core\Injector\InjectorLoader;
use SilverStripe\Dev\TestOnly;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class APIAwareRectorTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        $config = ConfigLoader::inst();
        $injector = InjectorLoader::inst();

        // Clear out the manifests, they may be populated if ran with the other test cases
        while ($config->hasManifest()) {
            $config->popManifest();
        }

        while ($injector->hasManifest()) {
            $injector->popManifest();
        }
    }

    public function testRefactorException(): void
    {
        /** @phpstan-ignore-next-line (This is an anonymous class and does not require '@see') */
        $rector = new class extends AbstractAPIAwareRector implements TestOnly {
            public function getRuleDefinition(): RuleDefinition
            {
                return new RuleDefinition('', []);
            }

            public function getNodeTypes(): array
            {
                return [];
            }

            public function refactorAPIAwareNode(Node $node): ?Node
            {
                return null;
            }
        };

        $this->expectException(ShouldNotHappenException::class);

        $rector->refactor(new class extends NodeAbstract implements TestOnly {
            public function getType(): string
            {
                return '';
            }

            /**
             * @return string[]
             */
            public function getSubNodeNames(): array
            {
                return [];
            }
        });
    }
}
