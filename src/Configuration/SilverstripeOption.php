<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Configuration;

final class SilverstripeOption
{
    /**
     * @var string
     */
    public const PHPSTAN_FOR_RECTOR_PATH = __DIR__ . '/../../config/phpstan/config.neon';

    /**
     * @var string
     */
    public const PHPSTAN_FOR_RECTOR_SILVERSTRIPE_53_PATH = __DIR__ . '/../../config/phpstan/silverstripe53.neon';

    /**
     * @var string
     */
    public const PHPSTAN_FOR_RECTOR_INCLUDE_TEST_ONLY_PATH = __DIR__ . '/../../config/phpstan/includeTestOnly.neon';
}
