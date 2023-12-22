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

namespace Splash\Metadata\Collectors;

use ReflectionAttribute;
use Splash\Client\Splash;
use Splash\Metadata\Interfaces\ObjectMetadataCollector;
use Splash\Metadata\Interfaces\ObjectMetadataConfigurator;
use Splash\Metadata\Mapping\ObjectMetadata;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Collect Splash Objects Metadata using PHP Attributes
 */
#[AutoconfigureTag(ObjectMetadataCollector::OBJECT_COLLECTOR)]
class AttributesObjectsMetadata implements ObjectMetadataCollector
{
    /**
     * @inheritDoc
     */
    public function configureObjectMetadata(ObjectMetadata $objectMetadata, string $objectClass): void
    {
        //==============================================================================
        // Build Reflexion Class
        $reflexion = new \ReflectionClass($objectClass);
        //==============================================================================
        // Walk on PHP Attributes
        foreach ($reflexion->getAttributes() as $phpAttribute) {
            //==============================================================================
            // Filter on Splash Metadata Configurator
            if (!$objectConfigurator = $this->isObjectConfigurator($phpAttribute)) {
                continue;
            }
            //==============================================================================
            // Configure Object Metadata
            $objectConfigurator->configure($objectMetadata);
        }
    }

    /**
     * Check if PHP Attribute is an Object Configurator
     */
    private function isObjectConfigurator(ReflectionAttribute $phpAttribute): ?ObjectMetadataConfigurator
    {
        if (!is_subclass_of($phpAttribute->getName(), ObjectMetadataConfigurator::class)) {
            return null;
        }
        $configurator = $phpAttribute->newInstance();

        return ($configurator instanceof ObjectMetadataConfigurator) ? $configurator : null;
    }
}
