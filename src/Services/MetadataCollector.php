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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Splash\Metadata\Interfaces\FieldsMetadataCollector;
use Splash\Metadata\Interfaces\ObjectMetadataCollector;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Splash\Metadata\Mapping\ObjectMetadata;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * Collect Medata for All Class Fields
 */
class MetadataCollector // extends FieldsFactory
{
    /**
     * @var array<class-string, ObjectMetadata>
     */
    private array $objectsMetadata = array();

    /**
     * @var array<class-string, FieldsMetadataCollection>
     */
    private array $fieldCollections = array();

    /**
     * @param ObjectMetadataCollector[] $objectMetadataCollectors
     * @param FieldsMetadataCollector[] $fieldsMetadataCollectors
     */
    public function __construct(
        #[TaggedIterator(ObjectMetadataCollector::OBJECT_COLLECTOR)]
        private readonly iterable $objectMetadataCollectors,
        #[TaggedIterator(FieldsMetadataCollector::FIELDS_COLLECTOR)]
        private readonly iterable $fieldsMetadataCollectors,
    ) {
    }

    /**
     * Collect Metadata for An Object Class
     *
     * @param class-string $class
     *
     * @return ObjectMetadata
     */
    public function getObjectMetadata(string $class): ObjectMetadata
    {
        if (!isset($this->objectsMetadata[$class])) {
            $this->objectsMetadata[$class] = new ObjectMetadata($class);
            foreach ($this->objectMetadataCollectors as $metadataCollector) {
                $metadataCollector->configureObjectMetadata($this->objectsMetadata[$class], $class);
            }
        }

        return $this->objectsMetadata[$class];
    }

    /**
     * Get & Publish Splash object Fields
     *
     * @param string $class
     *
     * @return array
     */
    public function getFields(string $class): array
    {
        return $this->getFieldsMetadata($class)->publish();
    }

    public function getField(string $class, string $identifier): ?FieldMetadata
    {
        return $this->getFieldsMetadata($class)->get($identifier);
    }

    public function getListedFields(string $class): Collection
    {
        $expressionBuilder = Criteria::expr();

        $criteria = new Criteria();
        $criteria->where($expressionBuilder->eq('inlist', true));

        return $this->getFieldsMetadata($class)->matching($criteria);
    }

    /**
     * @param class-string $class
     *
     * @return FieldsMetadataCollection
     */
    private function getFieldsMetadata(string $class): FieldsMetadataCollection
    {
        if (!isset($this->fieldCollections[$class])) {
            $this->fieldCollections[$class] = new FieldsMetadataCollection();

            foreach ($this->fieldsMetadataCollectors as $metadataCollector) {
                $metadataCollector->getFieldsMetadata($this->fieldCollections[$class], $class);
            }
        }

        return $this->fieldCollections[$class];
    }
}
