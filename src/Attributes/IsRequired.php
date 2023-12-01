<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Mark Field as Required
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsRequired implements FieldMetadataConfigurator
{
    public function __construct(
        protected bool $required = true,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        if ($this->required) {
            $metadata
                ->setRequired(true)
            ;
        }
    }
}