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

namespace Splash\Metadata\Test\Controller;

use Splash\Metadata\Test\Samples\PhpAttributes\IsReadOnly;
use Splash\Metadata\Test\Samples\PhpAttributes\IsTagged;
use Splash\Metadata\Test\Samples\PhpAttributes\IsWriteOnly;
use Splash\Metadata\Test\Traits\ObjectMetadataAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Php Attributes Objects Metadata Parsing
 */
class T00PhpObjectsAttributesCollectorTest extends WebTestCase
{
    use ObjectMetadataAssertionsTrait;

    /**
     * Test Detection of Field Basic Configuration
     */
    public function testConfigDetection(): void
    {
        $this->validateObjectMetadata(IsTagged::class, array(
            "name" => "Name",
            "description" => "Description",
            "ico" => "Ico",
        ));
        $this->validateObjectMetadata(IsReadOnly::class, array(
            "allowPushCreated" => false,
            "allowPushUpdated" => false,
            "allowPushDeleted" => false,
            "enablePushCreated" => false,
            "enablePushUpdated" => false,
            "enablePushDeleted" => false,
        ));
        $this->validateObjectMetadata(IsWriteOnly::class, array(
            "enablePullCreated" => false,
            "enablePullUpdated" => false,
            "enablePullDeleted" => false,
        ));
    }
}
