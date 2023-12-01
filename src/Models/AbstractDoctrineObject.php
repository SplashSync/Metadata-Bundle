<?php

namespace Splash\Metadata\Models;

use Doctrine\ORM\EntityManagerInterface;
use Splash\Bundle\Helpers\Doctrine\CrudHelperTrait;
use Splash\Bundle\Helpers\Doctrine\ObjectsListHelperTrait;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Metadata\Models\Objects\MetadataAwareObjectTrait;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\Metadata\Services\MetadataCollector;
use Splash\Metadata\Services\PropertyReader;
use Splash\Metadata\Services\PropertySetter;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Metadata\Models\Objects\MetadataFieldsAwareTrait;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDoctrineObject extends AbstractStandaloneObject
{

    // Splash Php Core Traits
    use IntelParserTrait;
    use MetadataFieldsAwareTrait;


    use CrudHelperTrait;
    use ObjectsListHelperTrait;
    use MetadataAwareObjectTrait;

    /**
     * @param class-string $objectClass
     */
    public function __construct(
        protected readonly string            $objectClass,
        EntityManagerInterface               $entityManager,
        protected readonly MetadataAdapter $metadataAdapter,
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