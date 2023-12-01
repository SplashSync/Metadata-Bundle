<?php

namespace Splash\Metadata\Models\Objects;

use Splash\Core\SplashCore as Splash;
use Splash\Metadata\Mapping\ObjectMetadata;
use Splash\Metadata\Services\MetadataAdapter;

trait MetadataAwareObjectTrait
{
    protected readonly MetadataAdapter $metadataAdapter;

    private ObjectMetadata $objectMetadata;

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->getObjectMetadata()->type ?? parent::getType();
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->getObjectMetadata()->name ?? parent::getName();
    }

    /**
     * @inheritdoc
     */
    public function getDesc(): string
    {
        return $this->getObjectMetadata()->description ?? parent::getDesc();
    }

    public function description(): array
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

        //====================================================================//
        // Build & Return Object Description Array
        $description = array(
            //====================================================================//
            // General Object definition
            //====================================================================//
            // Object Type Name
            "type" => $this->getType(),
            // Object Display Name
            "name" => $this->getName(),
            // Object Description
            "description" => $this->getDesc(),
            // Object Icon Class (Font Awesome or Glyph. ie "fa fa-user")
            "icon" => $this->getObjectMetadata()->ico ?? parent::getIcon(),
            // Is This Object Enabled or Not?
            "disabled" => $this->isDisabled(),
            //====================================================================//
            // Object Limitations
            "allow_push_created" => $this->getObjectMetadata()->allowPushCreated ?? static::$allowPushCreated,
            "allow_push_updated" => $this->getObjectMetadata()->allowPushUpdated ?? static::$allowPushUpdated,
            "allow_push_deleted" => $this->getObjectMetadata()->allowPushDeleted ?? static::$allowPushDeleted,
            //====================================================================//
            // Object Default Configuration
            "enable_push_created" => $this->getObjectMetadata()->enablePullCreated ?? static::$enablePushCreated,
            "enable_push_updated" => $this->getObjectMetadata()->enablePushUpdated ?? static::$enablePushUpdated,
            "enable_push_deleted" => $this->getObjectMetadata()->enablePushDeleted ?? static::$enablePushDeleted,
            "enable_pull_created" => $this->getObjectMetadata()->enablePullCreated ?? static::$enablePullCreated,
            "enable_pull_updated" => $this->getObjectMetadata()->enablePullUpdated ?? static::$enablePullUpdated,
            "enable_pull_deleted" => $this->getObjectMetadata()->enablePullDeleted ?? static::$enablePullDeleted
        );

        //====================================================================//
        // Apply Overrides & Return Object Description Array
        return Splash::configurator()->overrideDescription(static::getType(), $description);
    }

    /**
     * Get Object Metadata from Adapter
     */
    private function getObjectMetadata(): ObjectMetadata
    {
        return $this->objectMetadata ??= $this->metadataAdapter->getObject($this->objectClass);
    }
}