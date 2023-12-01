<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Mark Field as Read Only
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsReadOnly implements FieldMetadataConfigurator
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
                ->setRead(true)
                ->setWrite(false)
            ;
        }
    }
}