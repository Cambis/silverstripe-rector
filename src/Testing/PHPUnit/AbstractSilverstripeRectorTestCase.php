<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Testing\PHPUnit;

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Common test case for Silverstripe Rector.
 */
abstract class AbstractSilverstripeRectorTestCase extends AbstractRectorTestCase
{
    protected function setUp(): void
    {
        // Set these parameters here as they don't seem to persist correctly otherwise
        /** @phpstan-ignore-next-line classConstant.internal */
        SimpleParameterProvider::setParameter(Option::PHPSTAN_FOR_RECTOR_PATHS, [
            SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH,
            SilverstripeOption::PHPSTAN_FOR_RECTOR_INCLUDE_TEST_ONLY_PATH,
        ]);
        parent::setUp();
    }
}
