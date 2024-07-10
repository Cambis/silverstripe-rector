<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Rector\Config\RectorConfig;
use Rector\ValueObject\Visibility;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Visibility\ValueObject\ChangeMethodVisibility;

return static function (RectorConfig $rectorConfig): void {
    // Change the visiblity of hook methods to protected
    $rectorConfig->ruleWithConfiguration(
        ChangeMethodVisibilityRector::class,
        array_map(
            static function (string $hookMethodName): ChangeMethodVisibility {
                return new ChangeMethodVisibility('SilverStripe\Core\Extension', $hookMethodName, Visibility::PROTECTED);
            },
            SilverstripeConstants::PROTECTED_HOOK_METHODS
        )
    );
};
