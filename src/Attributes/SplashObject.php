<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Interfaces\ObjectMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Metadata\Mapping\ObjectMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Splash Object Definition
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SplashObject implements ObjectMetadataConfigurator
{
    public function __construct(
        private readonly ?string $type = null,
        private readonly ?string $name = null,
        private readonly ?string $description = null,
        private readonly ?string $ico = null,
        private readonly ?bool $allowPushCreated = null,
        private readonly ?bool $allowPushUpdated = null,
        private readonly ?bool $allowPushDeleted = null,
        private readonly ?bool $enablePushCreated = null,
        private readonly ?bool $enablePushUpdated = null,
        private readonly ?bool $enablePushDeleted = null,
        private readonly ?bool $enablePullCreated = null,
        private readonly ?bool $enablePullUpdated = null,
        private readonly ?bool $enablePullDeleted = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(ObjectMetadata $metadata): void
    {
        foreach (get_object_vars($this) as $property => $value) {
            if (!is_null($value)) {
                $metadata->{$property} = $value;
            }
        }
    }
}