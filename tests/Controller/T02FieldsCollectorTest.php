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

use Splash\Metadata\Test\Samples\PhpAttributes\IsFlagged;
use Splash\Metadata\Test\Traits\MetadataCollectorAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Php Field Metadata Collector
 */
class T02FieldsCollectorTest extends WebTestCase
{
    use MetadataCollectorAssertionsTrait;

    /**
     * Test Detection of Required Flag
     */
    public function testRequiredDetection(): void
    {
        $this->validateFieldRequired(IsFlagged::class, "requiredFromFlags");
        $this->validateFieldRequired(IsFlagged::class, "requiredFromAttribute");
    }

    /**
     * Test Detection of Used on Create Flag
     */
    public function testUsedOnCreateDetection(): void
    {
        $this->validateFieldOnCreate(IsFlagged::class, "onCreateFromFlags");
        $this->validateFieldOnCreate(IsFlagged::class, "onCreateFromAttribute");
    }

    /**
     * Test Detection of Listed Flag
     */
    public function testListedDetection(): void
    {
        $this->validateFieldListed(IsFlagged::class, "listedFromFlags");
    }
}
