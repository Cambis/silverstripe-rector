<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Set\SetProvider;

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Composer\InstalledVersions;
use OutOfBoundsException;
use Override;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Set\ValueObject\Set;
use function class_exists;
use function str_starts_with;

final class SilverstripeSetProvider implements SetProviderInterface
{
    #[Override]
    public function provide(): array
    {
        $sets = [
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '5.0',
                SilverstripeSetList::SILVERSTRIPE_50
            ),
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '5.1',
                SilverstripeSetList::SILVERSTRIPE_51
            ),
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '5.2',
                SilverstripeSetList::SILVERSTRIPE_52
            ),
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '5.3',
                SilverstripeSetList::SILVERSTRIPE_53
            ),
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '5.4',
                SilverstripeSetList::SILVERSTRIPE_54
            ),
            new ComposerTriggeredSet(
                'silverstripe',
                'silverstripe/framework',
                '6.0',
                SilverstripeSetList::SILVERSTRIPE_60
            ),
            new Set(
                'silverstripe',
                'Code quality',
                SilverstripeSetList::CODE_QUALITY
            ),
        ];

        if ($this->isInstalledVersion('silverstripe/framework', 5, 2)) {
            return $sets;
        }

        return [
            // new ComposerTriggeredSet(
            //     'silverstripe',
            //     'silverstripe/framework',
            //     '4.13',
            //     SilverstripeSetList::SILVERSTRIPE_413
            // ),
            ...$sets,
        ];
    }

    private function isInstalledVersion(string $package, int $majorVersion, int $minorVersion): bool
    {
        /** @phpstan-ignore-next-line */
        if (!class_exists(InstalledVersions::class)) {
            return false;
        }

        try {
            $installedVersion = InstalledVersions::getVersion($package);
        } catch (OutOfBoundsException) {
            return false;
        }

        return $installedVersion !== null && str_starts_with($installedVersion, $majorVersion . '.' . $minorVersion);
    }
}
