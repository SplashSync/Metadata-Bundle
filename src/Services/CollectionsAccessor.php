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
 * Access to Object Collections Properties
 */
class CollectionsAccessor
{
    /**
     * Add Child Item to Object Collection
     */
    public function addItem(object $object, object $item, string|FieldMetadata $field): bool
    {
        //====================================================================//
        // Add with Given Adder Method
        if (($field instanceof FieldMetadata) && ($addMethod = $field->getAdder())) {
            if (is_callable(array($object, $addMethod))) {
                $object->{ $addMethod }($item);

                return true;
            }

            return false;
        }
        //====================================================================//
        // Extract Field Id
        $fieldId = ($field instanceof FieldMetadata) ? $field->getFieldId() : $field;
        //====================================================================//
        // Add Item with Generic Add Method Detection
        $method = 'add'.ucfirst($fieldId);
        if (method_exists($object, $method)) {
            $object->{$method}($item);

            return true;
        }
        //====================================================================//
        // Array =>> Direct Append to Property
        if (property_exists($object, $fieldId) && is_array($object->{$fieldId})) {
            $object->{$fieldId}[] = $item;

            return true;
        }

        return false;
    }

    /**
     * Remove Child Item to Object Collection
     */
    public function removeItem(object $object, object $item, string|FieldMetadata $field): bool
    {
        //====================================================================//
        // Remove with Given Remover Method
        if (($field instanceof FieldMetadata) && ($removerMethod = $field->getRemover())) {
            if (is_callable(array($object, $removerMethod))) {
                $object->{ $removerMethod }($item);

                return true;
            }

            return false;
        }

        //====================================================================//
        // Extract Field Id
        $fieldId = ($field instanceof FieldMetadata) ? $field->getFieldId() : $field;
        //====================================================================//
        // Remove Item with Generic Remove Method Detection
        $method = 'remove'.ucfirst($fieldId);
        if (method_exists($object, $method)) {
            $object->{$method}($item);

            return true;
        }
        //====================================================================//
        // Array =>> Direct Unset to Property
        if (property_exists($object, $fieldId) && is_array($object->{$fieldId})) {
            if (false !== ($arrayKey = array_search($item, $object->{$fieldId}, true))) {
                unset($object->{$fieldId}[$arrayKey]);

                return true;
            }
        }

        return false;
    }
}
