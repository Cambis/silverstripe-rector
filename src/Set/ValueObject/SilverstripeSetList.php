<?php

declare(strict_types=1);

namespace SilverstripeRector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class SilverstripeSetList implements SetListInterface
{
    public const CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    public const SILVERSTRIPE_413 = __DIR__ . '/../../../config/set/silverstripe413.php';

    public const SILVERSTRIPE_50 = __DIR__ . '/../../../config/set/silverstripe50.php';

    public const SILVERSTRIPE_51 = __DIR__ . '/../../../config/set/silverstripe51.php';

    public const SILVERSTRIPE_52 = __DIR__ . '/../../../config/set/silverstripe52.php';
}
