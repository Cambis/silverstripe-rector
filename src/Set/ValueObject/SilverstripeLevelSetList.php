<?php

declare(strict_types=1);

namespace SilverstripeRector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class SilverstripeLevelSetList implements SetListInterface
{
    public const UP_TO_SS_4_13 = __DIR__ . '/../../../config/set/level/up-to-silverstripe413.php';

    public const UP_TO_SS_5_0 = __DIR__ . '/../../../config/set/level/up-to-silverstripe50.php';

    public const UP_TO_SS_5_1 = __DIR__ . '/../../../config/set/level/up-to-silverstripe51.php';

    public const UP_TO_SS_5_2 = __DIR__ . '/../../../config/set/level/up-to-silverstripe52.php';
}
