<?php

namespace Splash\Metadata\Interfaces;

use Splash\Metadata\Mapping\ObjectMetadata;

/**
 * Splash Object Metadata Configurator Interface
 */
interface ObjectMetadataConfigurator
{
    /**
     * Configure Object Metadata
     */
    public function configure(ObjectMetadata $metadata): void;
}