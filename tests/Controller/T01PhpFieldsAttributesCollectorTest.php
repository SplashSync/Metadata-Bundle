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
use Splash\Metadata\Test\Samples\PhpAttributes\IsReadOnly;
use Splash\Metadata\Test\Samples\PhpAttributes\IsTagged;
use Splash\Metadata\Test\Samples\PhpAttributes\IsTyped;
use Splash\Metadata\Test\Samples\PhpAttributes\IsWriteOnly;
use Splash\Metadata\Test\Traits\FieldMetadataAssertionsTrait;
use Splash\Models\Fields\ObjectField;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Php Attributes Field Metadata Parsing
 */
class T01PhpFieldsAttributesCollectorTest extends WebTestCase
{
    use FieldMetadataAssertionsTrait;

    /**
     * Test Detection of Field Basic Configuration
     */
    public function testConfigDetection(): void
    {
        $this->validateFieldMetadata(IsTagged::class, "configFromPhp", array(
            "id" => "configFromPhp",
            "name" => "ConfigFromPhp",
            "desc" => "ConfigFromPhp",
            "group" => null,
        ));
        $this->validateFieldMetadata(IsTagged::class, "configFromAttribute", array(
            "id" => "configFromAttribute",
            "name" => "Name",
            "desc" => "Desc",
            "group" => "Group",
        ));
    }

    /**
     * Test Detection of Field Types
     */
    public function testTypeDetection(): void
    {
        $this->validateFieldMetadata(IsTyped::class, "phpString", array(
            "type" => SPL_T_VARCHAR,
        ));
        $this->validateFieldMetadata(IsTyped::class, "phpBool", array(
            "type" => SPL_T_BOOL,
        ));
        $this->validateFieldMetadata(IsTyped::class, "phpInt", array(
            "type" => SPL_T_INT,
        ));
        $this->validateFieldMetadata(IsTyped::class, "phpFloat", array(
            "type" => SPL_T_DOUBLE,
        ));
        $this->validateFieldMetadata(IsTyped::class, "phpDatetime", array(
            "type" => SPL_T_DATETIME,
        ));
        $this->validateFieldMetadata(IsTyped::class, "attrText", array(
            "type" => SPL_T_TEXT,
        ));
        $this->validateFieldMetadata(IsTyped::class, "attrUrl", array(
            "type" => SPL_T_URL,
        ));
        $this->validateFieldMetadata(IsTyped::class, "attrEmail", array(
            "type" => SPL_T_EMAIL,
        ));
    }

    /**
     * Test Detection of Required Flag
     */
    public function testRequiredDetection(): void
    {
        $expected = array("required" => true);

        $this->validateFieldMetadata(IsFlagged::class, "requiredFromFlags", $expected);
        $this->validateFieldMetadata(IsFlagged::class, "requiredFromAttribute", $expected);
    }

    /**
     * Test Detection of Indexed Flag
     */
    public function testIndexedDetection(): void
    {
        $expected = array("index" => true);

        $this->validateFieldMetadata(IsFlagged::class, "indexFromFlags", $expected);
        $this->validateFieldMetadata(IsFlagged::class, "indexFromAttribute", $expected);
    }

    /**
     * Test Detection of Primary Flag
     */
    public function testPrimaryDetection(): void
    {
        $expected = array("primary" => true);

        $this->validateFieldMetadata(IsFlagged::class, "primaryFromFlags", $expected);
        $this->validateFieldMetadata(IsFlagged::class, "primaryFromAttribute", $expected);
    }

    /**
     * Test Detection of Read Only Flag
     */
    public function testReadOnlyDetection(): void
    {
        $expected = array("read" => true, "write" => false);

        $this->validateFieldMetadata(IsReadOnly::class, "fromFlags", $expected);
        $this->validateFieldMetadata(IsReadOnly::class, "fromAttribute", $expected);
        $this->validateFieldMetadata(IsReadOnly::class, "fromPhp", $expected);
    }

    /**
     * Test Detection of Write Only Flag
     */
    public function testWriteOnlyDetection(): void
    {
        $expected = array("read" => false, "write" => true);

        $this->validateFieldMetadata(IsWriteOnly::class, "fromFlags", $expected);
        $this->validateFieldMetadata(IsWriteOnly::class, "fromAttribute", $expected);
    }

    /**
     * Test Detection of Microdata
     */
    public function testMicrodataDetection(): void
    {
        $expected = array(
            "itemtype" => "itemType",
            "itemprop" => "itemProp",
            "tag" => "79e87a07694079c125c71434410c1586"
        );

        $this->validateFieldMetadata(IsTagged::class, "microdata", $expected);
    }

    /**
     * Test Detection of Microdata
     */
    public function testSyncModesDetection(): void
    {
        $this->validateFieldMetadata(IsTagged::class, "preferNone", array(
            "syncmode" => ObjectField::MODE_NONE
        ));
        $this->validateFieldMetadata(IsTagged::class, "preferRead", array(
            "syncmode" => ObjectField::MODE_READ
        ));
        $this->validateFieldMetadata(IsTagged::class, "preferWrite", array(
            "syncmode" => ObjectField::MODE_WRITE
        ));
        $this->validateFieldMetadata(IsTagged::class, "preferBoth", array(
            "syncmode" => ObjectField::MODE_BOTH
        ));
    }
}
