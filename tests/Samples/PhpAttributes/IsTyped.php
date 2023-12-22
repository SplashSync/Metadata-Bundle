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

namespace Splash\Metadata\Test\Samples\PhpAttributes;

use Splash\Metadata\Attributes as SPL;

/**
 * Sample Class for Validation
 */
class IsTyped
{
    #[SPL\Field()]
    public string $phpString;

    #[SPL\Field()]
    public bool $phpBool;

    #[SPL\Field()]
    public int $phpInt;

    #[SPL\Field()]
    public float $phpFloat;

    #[SPL\Field()]
    public \DateTime $phpDatetime;

    #[SPL\Field(type: SPL_T_TEXT)]
    public string $attrText;

    #[SPL\Field(type: SPL_T_URL)]
    public string $attrUrl;

    #[SPL\Field(type: SPL_T_EMAIL)]
    public string $attrEmail;
}
