<?php

namespace App\Page\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ValidationResult;

final class AlternativeTitleExtension extends Extension
{
    private static array $db = [
        'AlternativeTitle' => 'Varchar(255)',
    ];

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldToTab('Root.Main', TextField::create('AlternativeTitle'));
    }

    protected function validate(ValidationResult $validationResult): void
    {
        if ($this->AlternativeTitle === 'Not allowed') {
            $validationResult->addFieldError('AlternativeTitle', 'Cannot use this value');
        }
    }
}
