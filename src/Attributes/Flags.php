<?php

declare(strict_types=1);

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\Models\Fields\ObjectField;

/**
 * Splash Field Access Definitions
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Flags implements FieldMetadataConfigurator
{
    public function __construct(
        //==============================================================================
        //      ACCESS PROPS
        private readonly ?bool $required = null,
        private readonly ?bool $read = null,
        private readonly ?bool $write = null,
        private readonly ?bool $index = null,
        private readonly ?bool $listed = null,
        private readonly ?bool $listHidden = null,
        //==============================================================================
        //      SYNC MODE
        private readonly ?bool $primary = null,
        //==============================================================================
        //      DATA LOGGING PROPS
        private readonly ?bool $logged = null,
        //==============================================================================
        //      EXTRA PROPS
        private readonly ?bool $searchable = null,
    ) {
    }


    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        //==============================================================================
        // Configure Required Flag
        if (isset($this->required)) {
            $metadata->setRequired($this->required);
        }
        //==============================================================================
        // Configure Read Flag
        if (isset($this->read)) {
            $metadata->setRead($this->read);
        }
        //==============================================================================
        // Configure Write Flag
        if (isset($this->write)) {
            $metadata->setWrite($this->write);
        }
        //==============================================================================
        // Configure Index Flag
        if (isset($this->index)) {
            $metadata->setIndex($this->index);
        }
        //==============================================================================
        // Configure In List Flag
        if (isset($this->listed)) {
            $metadata->setIsListed($this->listed);
        }
        //==============================================================================
        // Configure In List Hidden Flag
        if (isset($this->listHidden)) {
            $metadata->setIsListed($metadata->inlist || $this->listHidden);
            $metadata->setHiddenList($this->listHidden);
        }
        //==============================================================================
        // Configure Primary Flag
        if (isset($this->primary)) {
            $metadata->setPrimary($this->primary);
        }
        //==============================================================================
        // Configure Logged Flag
        if (isset($this->logged)) {
            $metadata->setIsLogged($this->logged);
        }
        //==============================================================================
        // Configure Searchable Flag
        if (isset($this->searchable)) {
            $metadata->setSearchable($this->searchable);
        }
    }
}