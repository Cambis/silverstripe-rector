<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Set\ValueObject;

final class SilverstripeSetList
{
    public const CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_413 = __DIR__ . '/../../../config/set/silverstripe413.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_50 = __DIR__ . '/../../../config/set/silverstripe50.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_51 = __DIR__ . '/../../../config/set/silverstripe51.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_52 = __DIR__ . '/../../../config/set/silverstripe52.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_53 = __DIR__ . '/../../../config/set/silverstripe53.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_54 = __DIR__ . '/../../../config/set/silverstripe54.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_60 = __DIR__ . '/../../../config/set/silverstripe60.php';

    /**
     * @deprecated since 2.2.0 use `COMPOSER_BASED` instead.
     */
    public const SILVERSTRIPE_61 = __DIR__ . '/../../../config/set/silverstripe61.php';

    /**
     * Provides all the custom services that are required in order to run all the rules.
     */
    public const WITH_RECTOR_SERVICES = __DIR__ . '/../../../config/rector-services.php';

    public const GORRIECOE_LINK_TO_SILVERSTRIPE_LINKFIELD = __DIR__ . '/../../../config/set/gorriecoe-link-to-silverstripe-linkfield.php';

    public const SHEADAWSON_LINKABLE_TO_SILVERSTRIPE_LINKFIELD = __DIR__ . '/../../../config/set/sheadawson-linkable-to-silverstripe-linkfield.php';

    /**
     * Change the visibility of public extension hook methods to protected.
     */
    public const PROTECT_EXTENSION_HOOKS = __DIR__ . '/../../../config/set/protect-extension-hooks.php';

    /**
     * Add phpdoc annotations to magic properties and methods.
     *
     * Automatically detects what rules should be applied based on your `composer.json` file.
     */
    public const TYPE_DECLARATION_DOCBLOCKS = __DIR__ . '/../../../config/set/type-declaration-docblocks.php';

    /**
     * Automatically detects what rules should be applied based on your `composer.json` file.
     */
    public const COMPOSER_BASED = __DIR__ . '/../../../config/set/composer-based.php';
}
