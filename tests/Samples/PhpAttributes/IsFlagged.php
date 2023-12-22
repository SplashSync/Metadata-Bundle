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
class IsFlagged
{
    #[SPL\Flags(required: true)]
    public ?string $requiredFromFlags;

    #[SPL\IsRequired()]
    public ?string $requiredFromAttribute;

    #[SPL\Flags(index: true)]
    public ?string $indexFromFlags;

    #[SPL\IsIndexed()]
    public ?string $indexFromAttribute;

    #[SPL\Flags(primary: true)]
    public ?string $primaryFromFlags;

    #[SPL\IsPrimary()]
    public ?string $primaryFromAttribute;
}
