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

use Splash\Metadata\Mapping\ObjectMetadata;

/**
 * Interface for All Splash Objects Metadata Collectors
 */
interface ObjectMetadataCollector
{
    const OBJECT_COLLECTOR = "splash.metadata.object.collector";

    /**
     * Configure Splash Object Metadata for a Class
     *
     * @param ObjectMetadata $objectMetadata
     * @param class-string   $objectClass
     *
     * @return void
     */
    public function configureObjectMetadata(ObjectMetadata $objectMetadata, string $objectClass): void;
}
