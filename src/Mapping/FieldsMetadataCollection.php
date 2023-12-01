<?php

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
    final public function getOrCreate(string $identifier, string $type): FieldMetadata
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
        return array_filter(array_map(function (FieldMetadata $field): ?array
        {
            return ($field->validate() && !$field->isExcluded())
                ? $field->toArray()
                : null
            ;
        }, $this->getValues()));
    }
}