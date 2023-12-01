<?php

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
        $fieldId = ($field instanceof FieldMetadata) ? $field->id : $field;
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
        $fieldId = ($field instanceof FieldMetadata) ? $field->id : $field;
        //====================================================================//
        // Write with Setter Method Detection
        foreach (array('set') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                $object->{$method}($fieldData);

                return true;
            }
        }
        //====================================================================//
        // Write to Property
        if (property_exists($object, $fieldId)) {
            $object->{$fieldId} = $fieldData;

            return true;
        }

        return null;
    }
}