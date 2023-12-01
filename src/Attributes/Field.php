<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Splash General Field Definition
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Field implements FieldMetadataConfigurator
{
    public function __construct(
        protected ?string $type = null,
        protected ?string $name = null,
        protected ?string $desc = null,
        protected ?string $group = null,
    ) {
    }


    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        //==============================================================================
        // Configure Type
        if ($this->type) {
            $metadata->type = $this->type;
        }
        //==============================================================================
        // Configure Name
        if ($this->name) {
            $metadata->setName($this->name);
        }
        //==============================================================================
        // Configure Description
        if ($this->desc) {
            $metadata->setDesc($this->desc);
        }
        //==============================================================================
        // Configure Group
        if ($this->group) {
            $metadata->setGroup($this->group);
        }
    }
}