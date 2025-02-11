<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Metadata\Test\Traits;

use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Services\MetadataAdapter;

trait MetadataCollectorAssertionsTrait
{
    /**
     * Validate Object Field is in Required Collection
     *
     * @param class-string $class
     */
    protected function validateFieldRequired(string $class, string $property): void
    {
        $adapter = $this->getMetadataAdapter($class);
        //====================================================================//
        // Load Field Metadata
        $metadata = $adapter->getField($class, $property);
        $this->assertInstanceOf(FieldMetadata::class, $metadata);
        //====================================================================//
        // Load Required Fields
        $requiredFields = $adapter->getRequiredFields($class);
        $this->assertInstanceof(FieldMetadata::class, $requiredFields[$property]);
    }

    /**
     * Validate Object Field is in On Create Collection
     *
     * @param class-string $class
     */
    protected function validateFieldOnCreate(string $class, string $property): void
    {
        $adapter = $this->getMetadataAdapter($class);
        //====================================================================//
        // Load Field Metadata
        $metadata = $adapter->getField($class, $property);
        $this->assertInstanceOf(FieldMetadata::class, $metadata);
        //====================================================================//
        // Load On Create Fields
        $onCreateFields = $adapter->getOnCreateFields($class);
        $this->assertInstanceof(FieldMetadata::class, $onCreateFields[$property]);
    }

    /**
     * Validate Object Field is in Listed Collection
     *
     * @param class-string $class
     */
    protected function validateFieldListed(string $class, string $property): void
    {
        $adapter = $this->getMetadataAdapter($class);
        //====================================================================//
        // Load Field Metadata
        $metadata = $adapter->getField($class, $property);
        $this->assertInstanceOf(FieldMetadata::class, $metadata);
        //====================================================================//
        // Load On Create Fields
        $listedFields = $adapter->getListedFields($class);
        $this->assertInstanceof(FieldMetadata::class, $listedFields[$property]);
    }

    /**
     * Get Object Field Metadata Adapter
     */
    private function getMetadataAdapter(string $class): MetadataAdapter
    {
        $adapter = $this->getContainer()->get(MetadataAdapter::class);
        //====================================================================//
        // Safety Checks
        $this->assertTrue(class_exists($class));
        $this->assertInstanceOf(MetadataAdapter::class, $adapter);

        return $adapter;
    }
}
