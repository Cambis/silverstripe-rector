<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\StaticTypeMapper\PhpDocParser\ExtensionTypeMapper;
use Rector\Config\RectorConfig;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;

return RectorConfig::configure()
    // Allow the use of `\SilverStripe\Core\Extensible&\SilverStripe\Core\Extension` which would normally resolve to NEVER.
    ->registerService(ExtensionTypeMapper::class, null, PhpDocTypeMapperInterface::class);
