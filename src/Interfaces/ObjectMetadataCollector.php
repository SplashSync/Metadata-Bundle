<?php

namespace Splash\Metadata\Interfaces;

use Splash\Metadata\Mapping\ObjectMetadata;

/**
 * Interface for All Splash Objects Metadata Collectors
 */
interface ObjectMetadataCollector
{
    const OBJECT_COLLECTOR = "splash.metadata.object.collector";

    /**
     * Configure Splash Object Metadata for a Class
     *
     * @param ObjectMetadata $objectMetadata
     * @param class-string $objectClass
     *
     * @return void
     */
    public function configureObjectMetadata(ObjectMetadata $objectMetadata, string $objectClass): void;
}