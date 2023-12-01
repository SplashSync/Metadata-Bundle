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

namespace Splash\Metadata\Models\Objects;

use Exception;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\Metadata\Services\MetadataCollector;
use Splash\Metadata\Services\PropertyReader;
use Splash\Metadata\Services\PropertySetter;
use Splash\OpenApi\Fields as ApiFields;

/**
 * Splash Simple Fields Access using Metadata
 */
trait MetadataFieldsAwareTrait
{
    protected readonly string            $objectClass;

    protected readonly MetadataAdapter $metadataAdapter;

//    /**
//     * Build Objects Fields from Metadata Model.
//     *
//     * @throws Exception
//     *
//     * @return void
//     */
//    protected function buildMetadataSimpleFields(): void
//    {
//        ApiFields\Builder::buildModelFields($this->fieldsFactory(), $this->visitor->getModel());
//    }
//
    /**
     * Read API Simple Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     *
     * @throws Exception
     */
    protected function getMetadataSimpleFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if Field Exists for Reading
        if (!$metadata = $this->metadataAdapter->getField($this->objectClass, $fieldName)) {
            return;
        }
        //====================================================================//
        // Read Data
        /** @phpstan-ignore-next-line  */
        $this->out[$fieldName] = $this->metadataAdapter->getData($metadata, $this->object);
        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     *
     * @param string                                       $fieldName Field Identifier / Name
     * @param null|array<string, null|array|scalar>|scalar $fieldData Field Data
     *
     * @throws Exception
     *
     * @return void
     */
    protected function setMetadataSimpleFields(string $fieldName, mixed $fieldData): void
    {
        //====================================================================//
        // Check if Field Exists for Reading
        if (!$metadata = $this->metadataAdapter->getField($this->objectClass, $fieldName)) {
            return;
        }
        //====================================================================//
        // Check if Field Allow Writing
        if (empty($metadata->write)) {
            return;
        }
        //====================================================================//
        // Write Data
        $updated = $this->metadataAdapter->setData($metadata, $this->object, $fieldData);
        //====================================================================//
        // Write Fail
        if (is_null($updated)) {
            return;
        }
        unset($this->in[$fieldName]);
        //====================================================================//
        // Data was Updated
        if ($updated) {
            $this->needUpdate();
//            $prefix = ApiFields\Descriptor::getSubResourcePrefix($fieldName);
//            if ($prefix) {
//                $this->needUpdate(ucfirst($prefix));
//            }
        }

//        dump($this->object);
    }
}
