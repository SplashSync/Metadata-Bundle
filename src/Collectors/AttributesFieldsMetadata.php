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

namespace Splash\Metadata\Collectors;

use ReflectionAttribute;
use ReflectionProperty;
use Splash\Client\Splash;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Collect Splash Objects Fields Metadata using PHP Attributes
 */
#[AutoconfigureTag(FieldsMetadataCollector::FIELDS_COLLECTOR, array("priority" => -1))]
class AttributesFieldsMetadata implements FieldsMetadataCollector
{
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
        foreach ($reflexion->getProperties() as $property) {
            $fieldMetadata = null;
            //==============================================================================
            // Walk on PHP Attributes
            foreach ($property->getAttributes() as $phpAttribute) {
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

        //==============================================================================
        // Walk on Defined Fields
        foreach ($collection as $fieldMetadata) {
            //==============================================================================
            // Filter on Fields with
            if (!($childId = $fieldMetadata->getChildId()) || !($childClass = $fieldMetadata->getChildClass())) {
                continue;
            }
            //==============================================================================
            // Walk on Field Configurators
            foreach ($this->getFieldConfigurators($childClass, $childId) as $fieldConfigurator) {
                $fieldConfigurator->configure($fieldMetadata);
            }
        }
    }

    /**
     * Get Splash Field Configurator Attributes for a Class & Property
     *
     * @return FieldMetadataConfigurator[]
     */
    private function getFieldConfigurators(string $objectClass, string $propertyName): array
    {
        static $reflexions;

        $reflexions ??= array();
        $fieldConfigurators = array();

        //==============================================================================
        // Build Reflexion Class
        try {
            $reflexion = $reflexions[$objectClass] ??= new \ReflectionClass($objectClass);
        } catch (\ReflectionException $e) {
            Splash::log()->report($e);

            return array();
        }

        //==============================================================================
        // Get Reflexion Class Property
        try {
            $property = $reflexion->getProperty($propertyName);
        } catch (\ReflectionException $e) {
            return array();
        }
        //==============================================================================
        // Walk on PHP Attributes
        foreach ($property->getAttributes() as $phpAttribute) {
            //==============================================================================
            // Filter on Splash Metadata Configurator
            if (!$fieldConfigurator = $this->isFieldConfigurator($phpAttribute)) {
                continue;
            }
            $fieldConfigurators[] = $fieldConfigurator;
        }

        return $fieldConfigurators;
    }

    /**
     * Check if PHP Attribute is a Field Configurator
     */
    private function isFieldConfigurator(ReflectionAttribute $phpAttribute): ?FieldMetadataConfigurator
    {
        if (!is_subclass_of($phpAttribute->getName(), FieldMetadataConfigurator::class)) {
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
        if ($phpType instanceof \ReflectionNamedType) {
            $metadata->type = match ($phpType->getName()) {
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
