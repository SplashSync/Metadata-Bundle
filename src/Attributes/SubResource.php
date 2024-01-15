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
use Splash\Metadata\Interfaces\SubResourceMetadataConfigurator;
use Splash\Metadata\Mapping\FieldMetadata;

/**
 * Mark Field as Sub-Resource
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class SubResource implements SubResourceMetadataConfigurator
{
    public function __construct(
        private readonly ?string $targetClass = null,
        private readonly ?bool $read = null,
        private readonly ?bool $write = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function configureParent(FieldMetadata $metadata): void
    {
        // Nothing to do there.
    }

    /**
     * @inheritDoc
     */
    public function configureChildren(FieldMetadata $parentMetadata, FieldMetadata $childMetadata): void
    {
        //==============================================================================
        // Configure Read Flag
        if (isset($this->read) || empty($parentMetadata->read)) {
            $childMetadata->setRead($this->read ?? false);
        }
        //==============================================================================
        // Configure Write Flag
        if (isset($this->write) || empty($parentMetadata->write)) {
            $childMetadata->setWrite($this->write ?? false);
        }
    }

    /**
     * @inheritDoc
     */
    public function getChildrenClass(\ReflectionProperty $reflectionProperty): ?string
    {
        //==============================================================================
        // Target Class is Defined by Attribute
        if (isset($this->targetClass) && class_exists($this->targetClass)) {
            return $this->targetClass;
        }
        //==============================================================================
        // Only One Type Defined in PHP Property
        $type = $reflectionProperty->getType();
        if (($type instanceof \ReflectionNamedType) && (class_exists($targetClass = $type->getName()))) {
            return $targetClass;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getChildrenID(FieldMetadata $parentMetadata, \ReflectionProperty $reflectionProperty): string
    {
        return sprintf("%s__%s", $parentMetadata->id, $reflectionProperty->getName());
    }
}
