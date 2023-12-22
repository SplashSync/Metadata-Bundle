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

namespace Splash\Metadata\Collectors;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Splash\Metadata\Helpers\Doctrine\MetadataConverter;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Splash Doctrine Objects Metadata Collector
 */
#[AutoconfigureTag(FieldsMetadataCollector::FIELDS_COLLECTOR)]
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
            try {
                $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            } catch (MappingException $e) {
                continue;
            }
            if (!is_array($fieldMapping) || (!$splType = MetadataConverter::toSplashType($fieldMapping['type']))) {
                continue;
            }
            //==============================================================================
            // Configure Field Metadata
            $fieldMetadata = $collection->getOrCreate($fieldMapping['fieldName'], $splType);
            $this->configure($fieldMetadata, $fieldMapping);
        }
        //==============================================================================
        // Walk on Class Associations
        foreach($classMetadata->getAssociationMappings() as $associationMapping) {
            $this->getAssociationMetadata($collection, $classMetadata, $associationMapping);
        }
    }

    /**
     * @inheritDoc
     */
    private function getAssociationMetadata(
        FieldsMetadataCollection $collection,
        ClassMetadataInfo $parentMetadata,
        array $associationMapping
    ): void {
        //==============================================================================
        // Load Doctrine Metadata for Class
        try {
            $classMetadata = $this->entityManager->getClassMetadata($associationMapping['targetEntity']);
        } catch (\Exception $ex) {
            return;
        }

        //==============================================================================
        // Walk on Class Fields
        foreach($classMetadata->getFieldNames() as $fieldName) {
            //==============================================================================
            // Check if Field is Managed
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            if (!is_array($fieldMapping) || (!$splType = MetadataConverter::toSplashType($fieldMapping['type']))) {
                continue;
            }
            //==============================================================================
            // One to One Relation
            //==============================================================================
            if ($parentMetadata->isSingleValuedAssociation($associationMapping['fieldName'])) {
                //==============================================================================
                // Build Child Object Field ID
                $fieldId = sprintf("%s__%s", $associationMapping['fieldName'], $fieldMapping['fieldName']);
                //==============================================================================
                // Configure Field Metadata
                $fieldMetadata = $collection->getOrCreate($fieldId, $splType);
                $fieldMetadata->setParent(
                    $collection->getOrCreate($associationMapping['fieldName']),
                    $associationMapping['targetEntity'],
                    $fieldMapping['fieldName'],
                );
                $this->configure($fieldMetadata, $fieldMapping, $classMetadata);
            }

            //==============================================================================
            // One/Many to Many Relation
            //==============================================================================
            if ($parentMetadata->isCollectionValuedAssociation($associationMapping['fieldName'])) {
                //==============================================================================
                // Build Objects Field ID
                $fieldId = sprintf("%s@%s", $fieldMapping['fieldName'], $associationMapping['fieldName']);
                //==============================================================================
                // Configure Field Metadata
                $fieldMetadata = $collection->getOrCreate($fieldId, $splType.LISTSPLIT.SPL_T_LIST);
                $fieldMetadata->setParent(
                    $collection->getOrCreate($associationMapping['fieldName']),
                    $associationMapping['targetEntity'],
                    $fieldMapping['fieldName'],
                );
                $this->configure($fieldMetadata, $fieldMapping, $classMetadata);
            }
        }
    }

    /**
     * Configure Field using Doctrine Metadata
     */
    private function configure(FieldMetadata $field, array $metadata, ClassMetadataInfo $classMetadata = null): void
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
            ->setObjectIdentifier(MetadataConverter::isIdentifier($metadata, $classMetadata))
            ->setExcluded(MetadataConverter::isExcluded($metadata))
            ->setRequired($field->required || MetadataConverter::isRequired($metadata))
            ->setWrite($field->write && !MetadataConverter::isReadOnly($metadata))
            ->setPrimary(MetadataConverter::isPrimary($metadata))
        ;
    }
}
