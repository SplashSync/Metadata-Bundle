<?php

namespace Splash\Metadata\Interfaces;

use Splash\Metadata\Mapping\FieldMetadata;

/**
 * Splash Field Metadata Configurator Interface
 */
interface FieldMetadataConfigurator
{
    /**
     * Configure Fields Metadata
     */
    public function configure(FieldMetadata $metadata): void;
}