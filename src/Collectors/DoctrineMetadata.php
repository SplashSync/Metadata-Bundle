<?php

namespace Splash\Metadata\Collectors;

use Doctrine\ORM\EntityManagerInterface;
use Splash\Metadata\Helpers\Doctrine\MetadataConverter;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;

class DoctrineMetadata implements FieldsMetadataCollector
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFieldsMetadata(FieldsMetadataCollection $collection, string $objectClass): void
    {
        //==============================================================================
        // Load Doctrine Metadata for Class
        try {
            $classMetadata = $this->entityManager->getClassMetadata($objectClass);
        } catch (\Exception $ex) {
            return;
        }
        //==============================================================================
        // Walk on Class Fields
        foreach($classMetadata->getFieldNames() as $fieldName) {
            //==============================================================================
            // Check if Field is Managed
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            if (!is_array($fieldMapping) || (!$splType = MetadataConverter::toSplashType($fieldMapping['type'])) ) {
                continue;
            }
            //==============================================================================
            // Configure Field Metadata
            $fieldMetadata = $collection->getOrCreate($fieldMapping['fieldName'], $splType);
            $this->configure($fieldMetadata, $fieldMapping);
        }
    }


    /**
     * Configure Field using Doctrine Metadata
     */
    private function configure(FieldMetadata $field, array $metadata): void
    {
        //==============================================================================
        // Field Name
        if (!$field->name) {
            $field->setName(ucwords($metadata['fieldName']));
        }
        //==============================================================================
        // Field Description
        if (!$field->hasDesc()) {
            $field->setDesc(ucwords($metadata['fieldName']));
        }
        //==============================================================================
        // Field Tags
        $field
            ->setObjectIdentifier(MetadataConverter::isIdentifier($metadata))
            ->setExcluded(MetadataConverter::isExcluded($metadata))
            ->setRequired($field->required || MetadataConverter::isRequired($metadata))
            ->setWrite($field->write && !MetadataConverter::isReadOnly($metadata))
            ->setPrimary(MetadataConverter::isPrimary($metadata))
        ;

    }


}