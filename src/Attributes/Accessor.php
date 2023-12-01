<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Splash Field Accessors Definitions
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Accessor implements FieldMetadataConfigurator
{
    public function __construct(
        private readonly ?string $getter = null,
        private readonly ?string $setter = null,
    ) {
    }


    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        //==============================================================================
        // Configure Getter
        if (isset($this->getter)) {
            $metadata->setGetter($this->getter);
        }
        //==============================================================================
        // Configure Setter
        if (isset($this->setter)) {
            $metadata->setSetter($this->setter);
        }
    }
}