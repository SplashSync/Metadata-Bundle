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

namespace Splash\Metadata\Services;

use Splash\Client\Splash;
use Splash\Metadata\Mapping\FieldMetadata;

class PropertySetter
{
    public function __construct(
        protected readonly MetadataCollector $fieldsProcessor,
        protected readonly PropertyAccessor $propertyAccessor,
        protected readonly CollectionsAccessor $collectionAccessor,
        protected readonly PropertyTransformer $propertyTransformer,
    ) {
    }

    /**
     * Set an Object Field Data using Metadata Parser
     *
     * @param mixed $fieldData
     */
    public function set(FieldMetadata $metadata, object &$object, $fieldData): ?bool
    {
        //====================================================================//
        // Safety Check
        if (empty($metadata->write)) {
            return null;
        }
        //====================================================================//
        // Detect Direct Child Fields Types
        if ($parentMetadata = $metadata->getParent()) {
            //====================================================================//
            // Write Child Fields Types
            return $this->setChildData($parentMetadata, $metadata, $object, $fieldData);
        }
        //====================================================================//
        // Detect List Fields Types
        if ($metadata->hasChildren()) {
            //====================================================================//
            // Write List Fields Types
            return $this->setListData($metadata, $object, $fieldData);
        }

        //====================================================================//
        // Write Simple Fields Types
        return $this->setSimpleData($metadata, $object, $fieldData);
    }

    /**
     * Set an Object Field Data
     *
     * @param mixed $fieldData
     */
    private function setSimpleData(FieldMetadata $metadata, object &$object, $fieldData): ?bool
    {
        //====================================================================//
        // Normalize Field Value Using Data Transformer
        $fieldData = $this->propertyTransformer->reverseTransform($object, $metadata, $fieldData);
        //====================================================================//
        // Compare Values
        $currentData = $this->propertyAccessor->getProperty($object, $metadata);
        if ($this->propertyTransformer->isSame($object, $metadata, $currentData, $fieldData)) {
            return false;
        }

        //====================================================================//
        // Write Object Property
        return $this->propertyAccessor->setProperty($object, $metadata, $fieldData);
    }

    /**
     * Set a Child Object Field Data
     *
     * @param mixed $fieldData
     */
    private function setChildData(
        FieldMetadata $parentMetadata,
        FieldMetadata $childMetadata,
        object &$object,
        $fieldData
    ): ?bool {
        //====================================================================//
        // Load Children Object
        $children = $this->propertyAccessor->getProperty($object, $parentMetadata);
        //====================================================================//
        // Empty Child & Empty Data => Nothing to do!
        if (empty($children) && empty($fieldData)) {
            return false;
        }
        //====================================================================//
        // Empty Child => Empty result
        if (empty($children)) {
            $children = $this->propertyAccessor->createProperty($object, $parentMetadata);
            if (empty($children)) {
                Splash::log()->err("Unable to create child property ".$parentMetadata->id);

                return false;
            }
        }

        //====================================================================//
        // Write Simple Fields Types
        return $this->setSimpleData($childMetadata, $children, $fieldData);
    }

    /**
     * Set a Child Object Field Data
     */
    private function setListData(FieldMetadata $parentMetadata, object &$object, mixed $fieldData): bool
    {
        $updated = false;
        //====================================================================//
        // Load Children Objects
        $originData = $this->propertyAccessor->getProperty($object, $parentMetadata);
        $originData = is_iterable($originData) ? (array) $originData : array();
        //====================================================================//
        // Check if Field is a List field
        if (is_iterable($fieldData)) {
            foreach ($fieldData as $itemData) {
                //====================================================================//
                // Safety Check => Item data Must be Iterable
                if (!is_iterable($itemData)) {
                    continue;
                }
                //====================================================================//
                // Update / Create Item
                $updated = $this->setListDataItem(
                    $parentMetadata,
                    $object,
                    $itemData,
                    array_shift($originData)
                ) || $updated;
            }
        }
        //====================================================================//
        // Delete Remaining Items from Collection
        foreach ($originData as $originItem) {
            if (!$this->collectionAccessor->removeItem($object, $originItem, $parentMetadata)) {
                Splash::log()->err("Unable to remove list item ".$parentMetadata->id);

                break;
            }
            $updated = true;
        }

        return $updated;
    }

    /**
     * Set a Child Object Field Data Item
     */
    private function setListDataItem(
        FieldMetadata $parentMetadata,
        object &$object,
        iterable $itemData,
        ?object $originItem
    ): ?bool {
        $updated = false;
        //====================================================================//
        // Load / Create Item
        $newItem = is_null($originItem);
        $originItem = $originItem ?: $this->propertyAccessor->createProperty($object, $parentMetadata);
        if (empty($originItem)) {
            Splash::log()->err("Unable to create list item ".$parentMetadata->id);

            return null;
        }
        //====================================================================//
        // Update Item Data
        foreach ($parentMetadata->getChildren() as $childMetadata) {
            //====================================================================//
            // Write Simple Fields Types
            $updated = $this->setSimpleData(
                $childMetadata,
                $originItem,
                $itemData[$childMetadata->getChildId()] ?? null
            ) || $updated;
        }
        //====================================================================//
        // Add New Item to Collection
        if ($newItem) {
            if (!$this->collectionAccessor->addItem($object, $originItem, $parentMetadata)) {
                Splash::log()->err("Unable to add list item ".$parentMetadata->id);

                return null;
            }
        }

        return $updated;
    }
}
