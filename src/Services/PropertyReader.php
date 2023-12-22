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

use Splash\Metadata\Mapping\FieldMetadata;

class PropertyReader
{
    public function __construct(
        protected readonly MetadataCollector $fieldsProcessor,
        protected readonly PropertyAccessor $propertyAccessor,
        protected readonly PropertyTransformer $propertyTransformer,
    ) {
    }

    /**
     * Get an Object Field Data
     *
     * @param FieldMetadata $metadata Field Metadata
     * @param object        $object   Object to Read
     *
     * @return null|array<string, null|array<string, null|array|scalar>|scalar>|scalar
     */
    public function get(FieldMetadata $metadata, object $object): array|float|bool|int|string|null
    {
        //====================================================================//
        // Detect Child / List Fields Types
        if ($parentMetadata = $metadata->getParent()) {
            //====================================================================//
            // Detect List Fields Types
            if ($metadata->isListField()) {
                //====================================================================//
                // Read List Fields Types
                return $this->getListData($parentMetadata, $metadata, $object);
            }

            //====================================================================//
            // Read Child Fields Types
            return $this->getChildData($parentMetadata, $metadata, $object);
        }

        //====================================================================//
        // Read Simple Fields Types
        return $this->getSimpleData($metadata, $object);
    }

    /**
     * Get a Simple Object Field Data
     */
    private function getSimpleData(FieldMetadata $metadata, object $object): array|float|bool|int|string|null
    {
        //====================================================================//
        // Read Property from Object
        $propertyValue = $this->propertyAccessor->getProperty($object, $metadata);

        //====================================================================//
        // Normalize Field Value Using Data Transformer
        return $this->propertyTransformer->transform($object, $metadata, $propertyValue);
    }

    /**
     * Get a Child Object Field Data
     */
    private function getChildData(
        FieldMetadata $parentMetadata,
        FieldMetadata $childMetadata,
        object &$object
    ): array|float|bool|int|string|null {
        $children = $this->propertyAccessor->getProperty($object, $parentMetadata);
        //====================================================================//
        // Empty Child => Empty result
        if (!is_object($children)) {
            return null;
        }

        //====================================================================//
        // Read Simple Fields Types
        return $this->getSimpleData($childMetadata, $children);
    }

    /**
     * Set a Child Object Field Data
     */
    private function getListData(FieldMetadata $parentMetadata, FieldMetadata $metadata, object &$object): ?array
    {
        //====================================================================//
        // Load Children Objects
        $rawData = $this->propertyAccessor->getProperty($object, $parentMetadata);
        //====================================================================//
        // Safety Check
        if (!is_iterable($rawData)) {
            return null;
        }
        //====================================================================//
        // Walk on List Items
        $listData = array();
        foreach ($rawData as $index => $item) {
            $listData[$index] = array(
                $metadata->getChildId() => is_object($item)
                    ? $this->getSimpleData($metadata, $item)
                    : null
            );
        }

        return $listData;
    }
}
