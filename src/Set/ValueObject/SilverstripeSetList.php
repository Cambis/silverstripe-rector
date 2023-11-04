<?php

declare(strict_types=1);

namespace SilverstripeRector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class SilverstripeSetList implements SetListInterface
{
    public const CODE_QUALITY = __DIR__ . '/../../../config/set/code-quality.php';

    public const SS_4_13 = __DIR__ . '/../../../config/set/silverstripe413.php';

    public const SS_5_0 = __DIR__ . '/../../../config/set/silverstripe50.php';

    public const SS_5_1 = __DIR__ . '/../../../config/set/silverstripe51.php';

    public const SS_5_2 = __DIR__ . '/../../../config/set/silverstripe51.php';
}
