<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Microdata implements FieldMetadataConfigurator
{
    public function __construct(
        private readonly string $itemType,
        private readonly string $itemProp,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        $metadata->setMicroData($this->itemType, $this->itemProp);
    }
}