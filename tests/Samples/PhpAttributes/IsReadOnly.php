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
    allowPushCreated: false,
    allowPushUpdated: false,
    allowPushDeleted: false,
    enablePushCreated: false,
    enablePushUpdated: false,
    enablePushDeleted: false,
)]
class IsReadOnly
{
    #[SPL\Flags(write: false)]
    public ?string $fromFlags = 'from Flags';

    #[SPL\IsReadOnly()]
    public ?string $fromAttribute = 'from Attributes';

    #[SPL\Field()]
    public readonly ?string $fromPhp;

    public function __construct()
    {
        $this->fromPhp = 'from Php';
    }
}
