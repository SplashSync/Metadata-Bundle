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

namespace Splash\Metadata\Mapping;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @extends ArrayCollection<string, FieldMetadata>
 */
class FieldsMetadataCollection extends ArrayCollection
{
    /**
     * Get or Create a new Field Metadata
     *
     * @param string $identifier
     * @param string $type
     *
     * @return FieldMetadata
     */
    final public function getOrCreate(string $identifier, string $type = SPL_T_VARCHAR): FieldMetadata
    {
        if (!$field = $this->get($identifier)) {
            $field = new FieldMetadata($type);
            $field->setIdentifier($identifier);

            $this->set($identifier, $field);
        }

        return $field;
    }

    /**
     * Publish Field from Metadata Collection
     *
     * @return array
     */
    final public function publish(): array
    {
        return array_filter(array_map(function (FieldMetadata $field): ?array {
            return (!$field->isExcluded() && $field->validate())
                ? $field->toArray()
                : null
            ;
        }, $this->toArray()));
    }
}
