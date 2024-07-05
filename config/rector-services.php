<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\PHPStanStaticTypeMapper\TypeMapper\ExtensionObjectTypeMapper;
use Cambis\SilverstripeRector\StaticTypeMapper\PhpDocParser\GenericTypeMapper;
use Rector\Config\RectorConfig;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;

return RectorConfig::configure()
    // Allow the use of `\SilverStripe\Core\Extensible&\SilverStripe\Core\Extension` which would normally resolve to NEVER.
    ->registerService(GenericTypeMapper::class, null, PhpDocTypeMapperInterface::class)
    ->registerService(ExtensionObjectTypeMapper::class, null, TypeMapperInterface::class);
