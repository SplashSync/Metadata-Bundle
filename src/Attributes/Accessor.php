<?php

declare(strict_types=1);

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Metadata\Attributes;

use Attribute;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;

/**
 * Splash Field Accessors Definitions
 *
 * Configure how this field is accessed by Splash Metadata Parser
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Accessor implements FieldMetadataConfigurator
{
    public function __construct(
        private readonly ?string $getter = null,
        private readonly ?string $setter = null,
        private readonly ?string $factory = null,
        private readonly ?string $adder = null,
        private readonly ?string $remover = null,
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
        //==============================================================================
        // Configure Factory
        if (isset($this->factory)) {
            $metadata->setFactory($this->factory);
        }
        //==============================================================================
        // Configure Item Adder
        if (isset($this->adder)) {
            $metadata->setAdder($this->adder);
        }
        //==============================================================================
        // Configure Item Remover
        if (isset($this->remover)) {
            $metadata->setRemover($this->remover);
        }
    }
}
