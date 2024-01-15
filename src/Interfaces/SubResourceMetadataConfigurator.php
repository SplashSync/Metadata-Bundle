<?php

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

namespace Splash\Metadata\Interfaces;

use Splash\Metadata\Mapping\FieldMetadata;

/**
 * Splash Su-Resource Metadata Configurator Interface
 */
interface SubResourceMetadataConfigurator
{
    /**
     * Configure Parent Field Metadata
     */
    public function configureParent(FieldMetadata $metadata): void;

    /**
     * Get Child Object Class from Attribute or PHP Reflexion Property
     *
     * @return null|class-string
     */
    public function getChildrenClass(\ReflectionProperty $reflectionProperty): ?string;

    /**
     * Get Child Field ID
     */
    public function getChildrenID(FieldMetadata $parentMetadata, \ReflectionProperty $reflectionProperty): string;

    /**
     * Configure Parent Field Metadata
     */
    public function configureChildren(FieldMetadata $parentMetadata, FieldMetadata $childMetadata): void;
}
