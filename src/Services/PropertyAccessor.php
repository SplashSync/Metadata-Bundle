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

/**
 * Access to Object Properties
 */
class PropertyAccessor
{
    /**
     * Extract Property from An Object
     *
     * @return null|mixed
     */
    public function getProperty(object $object, string|FieldMetadata $field): mixed
    {
        //====================================================================//
        // Read with Given Getter Method
        if (($field instanceof FieldMetadata) && ($getMethod = $field->getGetter())) {
            if (is_callable(array($object, $getMethod))) {
                return $object->{ $getMethod }();
            }
        }
        //====================================================================//
        // Extract Field Id
        $fieldId = ($field instanceof FieldMetadata) ? $field->getFieldId() : $field;
        //====================================================================//
        // Try Reading Using Generic Methods
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }
        //====================================================================//
        // Try Direct Reading
        if (property_exists($object, $fieldId)) {
            return $object->{$fieldId} ?? null;
        }

        return null;
    }

    /**
     * Update Property of an Object
     */
    public function setProperty(object &$object, string|FieldMetadata $field, mixed $fieldData): ?bool
    {
        //====================================================================//
        // Write with Given Setter Method
        if (($field instanceof FieldMetadata) && ($setMethod = $field->getSetter())) {
            if (is_callable(array($object, $setMethod))) {
                $object->{ $setMethod }($fieldData);

                return true;
            }
        }
        //====================================================================//
        // Extract Field Id
        $fieldId = ($field instanceof FieldMetadata) ? $field->getFieldId() : $field;
        //====================================================================//
        // Write with Setter Method Detection
        $method = 'set'.ucfirst($fieldId);
        if (method_exists($object, $method)) {
            $object->{$method}($fieldData);

            return true;
        }
        //====================================================================//
        // Write to Property
        if (property_exists($object, $fieldId)) {
            $object->{$fieldId} = $fieldData;

            return true;
        }

        return null;
    }

    /**
     * Create Child Property from An Object
     */
    public function createProperty(object $object, string|FieldMetadata $field): ?object
    {
        //====================================================================//
        // Create with Given Factory Method
        if (($field instanceof FieldMetadata) && ($factoryMethod = $field->getFactory())) {
            if (is_callable(array($object, $factoryMethod))) {
                return $object->{ $factoryMethod }();
            }
        }

        return null;
    }
}
