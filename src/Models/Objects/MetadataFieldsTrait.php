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
use Splash\Components\FieldsManager;
use Splash\Metadata\Services\MetadataAdapter;

/**
 * Splash Simple Fields Access using Metadata
 */
trait MetadataFieldsTrait
{
    protected readonly string            $objectClass;

    protected readonly MetadataAdapter $metadataAdapter;

    /**
     * Build Objects Fields from Metadata Model.
     *
     * @throws Exception
     *
     * @return void
     */
    protected function buildMetadataFields(): void
    {
        $this->fieldsFactory()->merge(
            $this->metadataAdapter->getFields($this->objectClass)
        );
    }

    /**
     * Read Fields using Metadata Parser
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     *
     * @throws Exception
     */
    protected function getMetadataFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if Field Exists for Reading
        if (!$metadata = $this->metadataAdapter->getField($this->objectClass, $fieldName)) {
            return;
        }
        //====================================================================//
        // Check if Field Allowed for Reading via Metadata
        if (!$metadata->isAllowedRead()) {
            return;
        }
        //====================================================================//
        // Read List Data
        if (FieldsManager::isListField($fieldName)) {
            $this->out = array_replace_recursive($this->out, array(
                FieldsManager::listName($fieldName) => $this->metadataAdapter->getData($metadata, $this->object)
            ));
            //====================================================================//
            // Read Simple Data
        } else {
            /** @phpstan-ignore-next-line  */
            $this->out[$fieldName] = $this->metadataAdapter->getData($metadata, $this->object);
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields using Metadata Parser
     *
     * @param string                                       $fieldName Field Identifier / Name
     * @param null|array<string, null|array|scalar>|scalar $fieldData Field Data
     *
     * @throws Exception
     *
     * @return void
     */
    protected function setMetadataFields(string $fieldName, mixed $fieldData): void
    {
        //====================================================================//
        // Check if Field Exists for Reading
        if (!$metadata = $this->metadataAdapter->getField($this->objectClass, $fieldName)) {
            return;
        }
        //====================================================================//
        // Check if Field Allow Writing
        // Check if Field Allowed for Writing via Metadata
        if (empty($metadata->write) || !$metadata->isAllowedWrite()) {
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
        // Data Not Updated
        if (!$updated) {
            return;
        }
        //====================================================================//
        // Mark Object as Updated
        $this->needUpdate();
        if ($parentMetadata = $metadata->getParent()) {
            $this->needUpdate(ucfirst($parentMetadata->getFieldId()));
        }
    }
}
