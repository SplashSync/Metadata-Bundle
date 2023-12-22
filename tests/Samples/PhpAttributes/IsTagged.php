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
#[SPL\SplashObject(
    name: "Name",
    description: "Description",
    ico: "Ico",
)]
class IsTagged
{
    #[SPL\Field()]
    public ?string $configFromPhp;

    #[SPL\Field(
        name:   "Name",
        desc:   "Desc",
        group:  "Group",
    )]
    public ?string $configFromAttribute;

    #[SPL\Microdata(itemType: "itemType", itemProp: "itemProp")]
    public ?string $microdata;

    #[SPL\PreferNone()]
    public ?string $preferNone;

    #[SPL\PreferRead()]
    public ?string $preferRead;

    #[SPL\PreferWrite()]
    public ?string $preferWrite;

    #[SPL\Field()]
    public ?string $preferBoth;
}
