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

namespace Splash\Metadata\Test\Traits;

use Splash\Metadata\Mapping\ObjectMetadata;
use Splash\Metadata\Services\MetadataAdapter;

trait ObjectMetadataAssertionsTrait
{
    /**
     * Validate Object Metadata configuration
     *
     * @param class-string               $class
     * @param array<string, null|scalar> $values
     *
     * @return void
     */
    protected function validateObjectMetadata(string $class, array $values): void
    {
        $adapter = $this->getContainer()->get(MetadataAdapter::class);
        //====================================================================//
        // Safety Checks
        $this->assertTrue(class_exists($class));
        $this->assertInstanceOf(MetadataAdapter::class, $adapter);
        //====================================================================//
        // Load Object Metadata
        $metadata = $adapter->getObject($class);
        $this->assertInstanceOf(ObjectMetadata::class, $metadata);
        //====================================================================//
        // Verify Object Metadata
        foreach ($values as $key => $value) {
            $this->assertObjectHasProperty($key, $metadata);
            $this->assertSame($metadata->{$key}, $value);
        }
    }
}
