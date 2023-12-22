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

use Splash\Metadata\Mapping\FieldsMetadataCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Interface for All Splash Field Metadata Collectors
 */
#[AutoconfigureTag(FieldsMetadataCollector::FIELDS_COLLECTOR)]
interface FieldsMetadataCollector
{
    const FIELDS_COLLECTOR = "splash.metadata.fields.collector";

    /**
     * Collect Fields Metadata for a Class
     *
     * @param FieldsMetadataCollection $collection
     * @param class-string             $objectClass
     *
     * @return void
     */
    public function getFieldsMetadata(FieldsMetadataCollection $collection, string $objectClass): void;
}
