<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;


/**
 * Mark Field as Prefer Write
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class PreferWrite implements FieldMetadataConfigurator
{
    public function __construct(
        private readonly ?string $syncMode = ObjectField::MODE_WRITE,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        //==============================================================================
        // Configure SyncMode
        if ($this->syncMode) {
            $metadata->setSyncMode($this->syncMode);
        }
    }
}