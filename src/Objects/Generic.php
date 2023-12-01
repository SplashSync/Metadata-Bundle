<?php

namespace Splash\Metadata\Objects;

use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Metadata\Collectors\DoctrineMetadata;
use Splash\Metadata\Models\AbstractDoctrineObject;
use Splash\Metadata\Services\MetadataCollector;
use Splash\Metadata\Test\Bundle\Entity\ShortEntity;

class Generic extends AbstractDoctrineObject
{

    protected static string $name = "Generic";

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        // TODO: Implement fields() method.
//        return array();

//        dd($this->fieldsProcessor->getFields(ShortEntity::class));

        return $this->metadataAdapter->getFields($this->objectClass);
    }



//    /**
//     * @inheritDoc
//     */
//    public function get(string $objectId, array $fields): ?array
//    {
//        // TODO: Implement get() method.ù
//
//        return null;
//    }
//
//    /**
//     * @inheritDoc
//     */
//    public function set(?string $objectId, array $objectData): ?string
//    {
//        // TODO: Implement set() method.
//
//        return null;
//    }

    /**
     * @inheritDoc
     */
    public function delete(string $objectId): bool
    {
        // TODO: Implement delete() method.

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getObjectIdentifier(): ?string
    {
        // TODO: Implement getObjectIdentifier() method.

        return null;
    }
}