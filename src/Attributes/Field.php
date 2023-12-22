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
use Splash\Components\FieldsManager;
use Splash\Metadata\Interfaces\FieldMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;

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
            $metadata->type = FieldsManager::isListField($metadata->type)
                ? $this->type.LISTSPLIT.SPL_T_LIST
                : $this->type
            ;
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
