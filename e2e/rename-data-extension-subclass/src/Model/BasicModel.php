<?php

namespace App\Model;

use App\Model\BasicModel\Extension\BasicExtension;
use SilverStripe\ORM\DataObject;

final class BasicModel extends DataObject
{
    private static array $extensions = [
        BasicExtension::class,
    ];
}
