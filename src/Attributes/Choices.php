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
 * Register Field Choices
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Choices implements FieldMetadataConfigurator
{
    /**
     * @param array<scalar, string> $choices
     */
    public function __construct(
        protected array $choices = array(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configure(FieldMetadata $metadata): void
    {
        foreach ($this->choices as $value => $description) {
            $metadata->addChoice((string) $value, $description);
        }
    }
}
