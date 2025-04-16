<?php

namespace SilverStripe\ORM\FieldType;

use function class_exists;

if (class_exists('SilverStripe\ORM\FieldType\DBHTMLVarchar')) {
    return;
}

class DBHTMLVarchar extends DBVarchar
{
}
