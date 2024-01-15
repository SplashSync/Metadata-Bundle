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
use Splash\Components\FieldsManager;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Interfaces\SubResourceMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Collect Splash Objects Fields Metadata using PHP Attributes
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
        // Walk on Class Properties to Collect Metadata
        $this->getPropertiesMetadata($collection, $objectClass);
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
     * Configure Metadata for All Class Properties
     *
     * @param class-string $objectClass
     */
    public function getPropertiesMetadata(
        FieldsMetadataCollection $collection,
        string $objectClass,
        FieldMetadata $parentMetadata = null,
        SubResourceMetadataConfigurator $parentConfigurator = null
    ): void {
        static $reflexions;
        $reflexions ??= array();

        //==============================================================================
        // Build Reflexion Class
        try {
            $reflexion = $reflexions[$objectClass] ??= new \ReflectionClass($objectClass);
        } catch (\ReflectionException $e) {
            return;
        }
        //==============================================================================
        // Walk on Class Properties
        foreach ($reflexion->getProperties() as $property) {
            $fieldMetadata = null;
            //==============================================================================
            // Walk on PHP Attributes
            foreach ($property->getAttributes() as $phpAttribute) {
                //==============================================================================
                // This a Field Metadata Configurator
                $fieldMetadata = $this->getMetadataFromFieldConfigurator(
                    $property,
                    $phpAttribute,
                    $collection,
                    $objectClass,
                    $parentMetadata,
                    $parentConfigurator
                );
                //==============================================================================
                // This a SubRessource Metadata Configurator
                $fieldMetadata ??= $this->getMetadataFromSubResourceConfigurator(
                    $property,
                    $phpAttribute,
                    $collection
                );
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
     * Collect Field Metadata from Field Configurator
     *
     * @param class-string $objectClass
     */
    public function getMetadataFromFieldConfigurator(
        ReflectionProperty $phpProperty,
        ReflectionAttribute $phpAttribute,
        FieldsMetadataCollection $collection,
        string $objectClass,
        FieldMetadata $parentMetadata = null,
        SubResourceMetadataConfigurator $parentConfigurator = null
    ): ?FieldMetadata {
        //==============================================================================
        // This a Field Metadata Configurator
        if (!$fieldConfigurator = $this->isFieldConfigurator($phpAttribute)) {
            return null;
        }
        //==============================================================================
        // Create Field Metadata from Parent
        if ($parentMetadata && $parentConfigurator) {
            $fieldMetadata = $collection->getOrCreate(
                $parentConfigurator->getChildrenID($parentMetadata, $phpProperty),
                ""
            );
            $fieldMetadata->setParent($parentMetadata, $objectClass, $phpProperty->getName());
        } else {
            $fieldMetadata = $collection->getOrCreate($phpProperty->getName(), "");
        }
        //==============================================================================
        // Configure Field Metadata
        $fieldConfigurator->configure($fieldMetadata);
        //==============================================================================
        // Configure Field Metadata from Parent
        if ($parentMetadata && $parentConfigurator) {
            $parentConfigurator->configureChildren($parentMetadata, $fieldMetadata);
        }

        return $fieldMetadata;
    }

    /**
     * Collect Field Metadata from Sub Resource Configurator
     */
    public function getMetadataFromSubResourceConfigurator(
        ReflectionProperty $phpProperty,
        ReflectionAttribute $phpAttribute,
        FieldsMetadataCollection $collection,
    ): ?FieldMetadata {
        //==============================================================================
        // This a SubRessource Metadata Configurator
        if (!$resourceConfigurator = $this->isSubResourceConfigurator($phpAttribute)) {
            return null;
        }
        //==============================================================================
        // Configure Parent Field Metadata
        $fieldMetadata = $collection->getOrCreate($phpProperty->getName(), "");
        $resourceConfigurator->configureParent($fieldMetadata);
        //==============================================================================
        // Configure Children Field Metadata
        $childrenClass = $resourceConfigurator->getChildrenClass($phpProperty);
        if ($childrenClass && class_exists($childrenClass)) {
            $this->getPropertiesMetadata($collection, $childrenClass, $fieldMetadata, $resourceConfigurator);
        }

        return $fieldMetadata;
    }

    /**
     * Get Splash Field Configurator Attributes for a Class & Property
     *
     * @param class-string $objectClass
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
     * Check if PHP Attribute is a Sub-Ressource Configurator
     */
    private function isSubResourceConfigurator(ReflectionAttribute $phpAttribute): ?SubResourceMetadataConfigurator
    {
        if (!is_subclass_of($phpAttribute->getName(), SubResourceMetadataConfigurator::class)) {
            return null;
        }
        $configurator = $phpAttribute->newInstance();

        return ($configurator instanceof SubResourceMetadataConfigurator) ? $configurator : null;
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
            if ($metadata->type && FieldsManager::isListField($metadata->id)) {
                $metadata->type .= LISTSPLIT.SPL_T_LIST;
            }
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
        //==============================================================================
        // Field Name
        $metadata->setName(ucwords($phpProperty->getName()));
        //==============================================================================
        // Field Description
        if (!$metadata->hasDesc()) {
            $metadata->setDesc(ucwords($phpProperty->getName()));
        }
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
