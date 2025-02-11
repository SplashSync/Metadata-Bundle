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
 * Mark this Field as used on Create
 *
 * Why? This field is not "required", so it may be empty on create,
 * but information must set in order to create object
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class UsedOnCreate implements FieldMetadataConfigurator
{
    public function __construct(
        private readonly ?bool $usedOnCreate = true,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        //==============================================================================
        // Configure Access Flag
        if (isset($this->usedOnCreate)) {
            $metadata->setUsedOnCreate($this->usedOnCreate);
        }
    }
}
