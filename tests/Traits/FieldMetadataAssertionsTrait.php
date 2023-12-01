<?php

namespace Splash\Metadata\Test\Traits;

use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Services\MetadataAdapter;

trait FieldMetadataAssertionsTrait
{
    /**
     * Validate Object Field Metadata configuration
     *
     * @param class-string $class
     * @param string $property
     * @param array<string, null|scalar> $values
     *
     * @return void
     */
    protected function validateFieldMetadata(string $class, string $property, array $values): void
    {
        $adapter = $this->getContainer()->get(MetadataAdapter::class);
        //====================================================================//
        // Safety Checks
        $this->assertTrue(class_exists($class));
        $this->assertInstanceOf(MetadataAdapter::class, $adapter);
        //====================================================================//
        // Load Field Metadata
        $metadata = $adapter->getField($class, $property);
        $this->assertInstanceOf(FieldMetadata::class, $metadata);
        //====================================================================//
        // Verify Field Metadata
        foreach ($values as $key => $value) {
            $this->assertObjectHasProperty($key, $metadata);
            $this->assertSame($metadata->{$key}, $value);
        }
    }
}