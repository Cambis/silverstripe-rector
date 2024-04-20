<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class SilverstripeLevelSetList implements SetListInterface
{
    public const UP_TO_SILVERSTRIPE_413 = __DIR__ . '/../../../config/set/level/up-to-silverstripe413.php';

    public const UP_TO_SILVERSTRIPE_50 = __DIR__ . '/../../../config/set/level/up-to-silverstripe50.php';

    public const UP_TO_SILVERSTRIPE_51 = __DIR__ . '/../../../config/set/level/up-to-silverstripe51.php';

    public const UP_TO_SILVERSTRIPE_52 = __DIR__ . '/../../../config/set/level/up-to-silverstripe52.php';
}
