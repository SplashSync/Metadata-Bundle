<?php

namespace Splash\Metadata\Interfaces;

use Splash\Metadata\Mapping\FieldsMetadataCollection;

/**
 * Interface for All Splash Field Metadata Collectors
 */
interface FieldsMetadataCollector
{
    const FIELDS_COLLECTOR = "splash.metadata.fields.collector";

    /**
     * Collect Fields Metadata for a Class
     *
     * @param FieldsMetadataCollection $collection
     * @param class-string $objectClass
     *
     * @return void
     */
    public function getFieldsMetadata(FieldsMetadataCollection $collection, string $objectClass): void;
}