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

namespace Splash\Metadata\Models;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Splash\Bundle\Helpers\Doctrine\CrudHelperTrait;
use Splash\Bundle\Helpers\Doctrine\ObjectsListHelperTrait;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Metadata\Models\Objects\MetadataFieldsTrait;
use Splash\Metadata\Models\Objects\MetadataObjectTrait;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\Models\Objects\IntelParserTrait;

/**
 * Base Class for Creating Standalone Object Service from Doctrine Entity
 */
abstract class AbstractDoctrineObject extends AbstractStandaloneObject
{
    //====================================================================//
    // Splash Php Core Traits
    use IntelParserTrait;
    //====================================================================//
    // Splash Metadata Parser Traits
    use MetadataObjectTrait;
    use MetadataFieldsTrait;
    //====================================================================//
    // Splash Doctrine Generic Objects Traits
    use CrudHelperTrait;
    use ObjectsListHelperTrait;

    /**
     * @param class-string $objectClass
     */
    public function __construct(
        protected readonly string            $objectClass,
        EntityManagerInterface               $entityManager,
        protected readonly MetadataAdapter   $metadataAdapter,
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($objectClass);
    }

    /**
     * @inheritDoc
     */
    protected function create(): ?object
    {
        try {
            $object = new $this->objectClass();
            $this->entityManager->persist($object);
        } catch (\Throwable|\TypeError $error) {
            return Splash::log()->errNull($error->getMessage());
        }

        return $object;
    }

    /**
     * Extract Array Data for List Requests
     */
    protected function getObjectListArray(object $object): array
    {
        return $this->metadataAdapter->extractListedFields($this->objectClass, $object);
    }

    /**
     * Configure Query Builder for List Requests
     */
    protected function setObjectListFilter(QueryBuilder $builder, string $filter): void
    {
        $this->metadataAdapter->configureListQueryBuilder($this->objectClass, $builder, $filter, "c");
    }
}
