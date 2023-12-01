<?php

namespace Splash\Metadata\Collectors;


use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionProperty;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Interfaces\ObjectMetadataCollector;
use Splash\Metadata\Interfaces\ObjectMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Splash\Metadata\Mapping\ObjectMetadata;

/**
 * Collect Splash Objects Metadata using PHP Attributes
 */
class AttributesMetadata implements ObjectMetadataCollector, FieldsMetadataCollector
{
    /**
     * @inheritDoc
     */
    public function configureObjectMetadata(ObjectMetadata $objectMetadata, string $objectClass): void
    {
        //==============================================================================
        // Build Reflexion Class
        $reflexion = new \ReflectionClass($objectClass);
        //==============================================================================
        // Walk on PHP Attributes
        foreach($reflexion->getAttributes() as $phpAttribute) {
            //==============================================================================
            // Filter on Splash Metadata Configurator
            if (!$objectConfigurator = $this->isObjectConfigurator($phpAttribute)) {
                continue;
            }
            //==============================================================================
            // Configure Object Metadata
            $objectConfigurator->configure($objectMetadata);
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldsMetadata(FieldsMetadataCollection $collection, string $objectClass): void
    {
        //==============================================================================
        // Build Reflexion Class
        $reflexion = new \ReflectionClass($objectClass);
        //==============================================================================
        // Walk on Class Properties
        foreach($reflexion->getProperties() as $property) {
            $fieldMetadata = null;
            //==============================================================================
            // Walk on PHP Attributes
            foreach($property->getAttributes() as $phpAttribute) {
                //==============================================================================
                // Filter on Splash Metadata Configurator
                if (!$fieldConfigurator = $this->isFieldConfigurator($phpAttribute)) {
                    continue;
                }
                //==============================================================================
                // Configure Field Metadata
                $fieldMetadata = $collection->getOrCreate($property->getName(), "");
                $fieldConfigurator->configure($fieldMetadata);
            }
            if (!isset($fieldMetadata)) {
                continue;
            }
            //==============================================================================
            // Complete Configure Using Property Metadata
            $this->configureType($property, $fieldMetadata);
            $this->configureName($property, $fieldMetadata);
            $this->configureMode($property, $fieldMetadata);
        }
    }

    /**
     * Check if PHP Attribute is an Object Configurator
     */
    private function isObjectConfigurator(ReflectionAttribute $phpAttribute): ?ObjectMetadataConfigurator
    {
        if (!is_subclass_of($phpAttribute->getName(),  ObjectMetadataConfigurator::class)) {
            return null;
        }
        $configurator = $phpAttribute->newInstance();

        return ($configurator instanceof ObjectMetadataConfigurator) ? $configurator : null;
    }

    /**
     * Check if PHP Attribute is a Field Configurator
     */
    private function isFieldConfigurator(ReflectionAttribute $phpAttribute): ?FieldMetadataConfigurator
    {
        if (!is_subclass_of($phpAttribute->getName(),  FieldMetadataConfigurator::class)) {
            return null;
        }
        $configurator = $phpAttribute->newInstance();

        return ($configurator instanceof FieldMetadataConfigurator) ? $configurator : null;
    }

    /**
     * Auto-Configure Field Type
     */
    private function configureType(ReflectionProperty $phpProperty, FieldMetadata $metadata): void
    {
        //==============================================================================
        // Already Done
        if (!empty($metadata->type)) {
            return;
        }
        //==============================================================================
        // Configure Type from PHP Property Type
        $phpType = $phpProperty->getType();
        if ($phpType instanceof ReflectionNamedType) {
            $metadata->type = match($phpType->getName()){
                "string" => SPL_T_VARCHAR,
                "bool" => SPL_T_BOOL,
                "int" => SPL_T_INT,
                "float" => SPL_T_DOUBLE,
                \DateTime::class, \DateTimeImmutable::class => SPL_T_DATETIME,
                default => null
            };
        }
    }


    /**
     * Auto-Configure Field Name
     */
    private function configureName(ReflectionProperty $phpProperty, FieldMetadata $metadata): void
    {
        //==============================================================================
        // Already Done
        if (!empty($metadata->name)) {
            return;
        }

        $metadata->name = ucwords($phpProperty->getName());
    }

    /**
     * Auto-Configure Field Mode
     */
    private function configureMode(ReflectionProperty $phpProperty, FieldMetadata $metadata): void
    {
        if ($phpProperty->isReadOnly()) {
            $metadata->setWrite(false);
        }
    }
}