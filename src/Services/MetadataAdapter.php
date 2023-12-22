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

use Doctrine\ORM\QueryBuilder;
use Splash\Client\Splash;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\ObjectMetadata;

/**
 * High Level Splash Generic Data Adapter
 * Use Medata to Access Objects Class Data
 */
class MetadataAdapter
{
    public function __construct(
        protected readonly MetadataCollector $collector,
        protected readonly PropertyReader $reader,
        protected readonly PropertySetter $setter,
        protected readonly PropertyAccessor $propertyAccessor,
    ) {
    }

    /**
     * Get Metadata for a given Class
     *
     * @param class-string $class
     *
     * @note Direct Forward to Metadata Collector
     */
    public function getObject(string $class): ObjectMetadata
    {
        return $this->collector->getObjectMetadata($class);
    }

    /**
     * Get All Available Fields for a Given Class
     *
     * @param class-string $class
     *
     * @note Direct Forward to Metadata Collector
     */
    public function getFields(string $class): array
    {
        return $this->collector->getFields($class);
    }

    /**
     * Get Metadata for a given Fields ID
     *
     * @param class-string $class
     *
     * @note Direct Forward to Metadata Collector
     */
    public function getField(string $class, string $identifier): ?FieldMetadata
    {
        return $this->collector->getField($class, $identifier);
    }

    /**
     * Get an Object Field Data
     *
     * @note Direct Forward to Property Reader
     */
    public function getData(FieldMetadata $metadata, object $object): mixed
    {
        try {
            return $this->reader->get($metadata, $object);
        } catch (\Throwable|\TypeError $ex) {
            return Splash::log()->errNull($ex);
        }
    }

    /**
     * Set an Object Field Data
     *
     * @note Direct Forward to Property Setter
     */
    public function setData(FieldMetadata $metadata, object $object, mixed $data): mixed
    {
        try {
            return $this->setter->set($metadata, $object, $data);
        } catch (\Throwable|\TypeError $ex) {
            return Splash::log()->errNull($ex);
        }
    }

    /**
     * Configure Doctrine List Query Builder for Data Filtering
     *
     * @param class-string $class
     */
    public function configureListQueryBuilder(
        string $class,
        QueryBuilder $builder,
        string $filter,
        string $alias = "c"
    ): void {
        $conditions = array();
        //====================================================================//
        // Walk on Listed Fields
        foreach ($this->getListedFields($class) as $fieldMetadata) {
            //====================================================================//
            // This is an Identifier Fields
            if ($fieldMetadata->isObjectIdentifier()) {
                $conditions[] = $builder->expr()->eq(
                    sprintf('%s.%s', $alias, $fieldMetadata->id),
                    ":eqFilter"
                );
                $builder->setParameter('eqFilter', $filter);

                continue;
            }
            //====================================================================//
            // This is an Searchable Fields
            if ($fieldMetadata->isSearchable()) {
                $conditions[] = $builder->expr()->like(
                    sprintf('%s.%s', $alias, $fieldMetadata->id),
                    ":textFilter"
                );
            }
        }
        //====================================================================//
        // No Conditions Defined
        if (!count($conditions)) {
            return;
        }
        //====================================================================//
        // Configure Query
        $orx = $builder->expr()->orX();
        foreach ($conditions as $condition) {
            $orx->add($condition);
        }
        $builder->andWhere($orx);
        $builder->setParameter('textFilter', "%".$filter."%");
    }

    /**
     * Extract Listed Fields from Object Using Metadata
     *
     * @param class-string $class
     * @param object       $object
     *
     * @return array
     */
    public function extractListedFields(string $class, object $object): array
    {
        //====================================================================//
        // Walk on Listed Fields
        $values = array();
        foreach ($this->getListedFields($class) as $fieldId => $fieldMetadata) {
            // Read property value
            $value = $this->propertyAccessor->getProperty($object, $fieldMetadata);
            // If Identifier => Force 'id' as Key
            $fieldId = $fieldMetadata->isObjectIdentifier() ? "id" : $fieldId;
            // Ensure Scalar Value
            $values[$fieldId] = is_scalar($value) ? (string) $value : null;
        }

        return $values;
    }

    /**
     * Get Collection of Listed Fields from Object Class Using Metadata
     *
     * @param class-string $class
     *
     * @return FieldMetadata[]
     */
    private function getListedFields(string $class): array
    {
        static $cache;

        //====================================================================//
        // Ensure Listed Fields Cache is Loaded
        $cache ??= array();
        $cache[$class] ??= $this->collector->getListedFields($class)->toArray();

        /** @var array<string, FieldMetadata[]> $cache */
        return $cache[$class];
    }
}
