<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Mark Field as Write Only
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsWriteOnly implements FieldMetadataConfigurator
{
    public function __construct(
        protected bool $enabled = true,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        if ($this->enabled) {
            $metadata
                ->setRead(false)
                ->setWrite(true)
            ;
        }
    }
}