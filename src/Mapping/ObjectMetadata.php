<?php

namespace Splash\Metadata\Mapping;

use Splash\Metadata\DataTransformer;
use Splash\Models\Fields\ObjectField;
use Splash\Metadata\Interfaces\SplashDataTransformer;

class ObjectMetadata
{
    public ?string $type = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $ico = null;
    public ?bool $allowPushCreated = null;
    public ?bool $allowPushUpdated = null;
    public ?bool $allowPushDeleted = null;
    public ?bool $enablePushUpdated = null;
    public ?bool $enablePushDeleted = null;
    public ?bool $enablePullCreated = null;
    public ?bool $enablePullUpdated = null;
    public ?bool $enablePulDeleted = null;

    /**
     * @param class-string $class
     */
    public function __construct(
        private readonly string $class,
    ) {
    }

    /**
     * Get Target Class
     *
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}