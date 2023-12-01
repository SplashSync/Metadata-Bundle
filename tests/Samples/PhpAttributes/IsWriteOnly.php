<?php

namespace Splash\Metadata\Test\Samples\PhpAttributes;

use Splash\Metadata\Attributes as SPL;

/**
 * Sample Class for Validation
 */
#[SPL\SplashObject(
    enablePullCreated: false,
    enablePullUpdated: false,
    enablePullDeleted: false,
)]
class IsWriteOnly
{
    #[SPL\Flags(read: false)]
    public ?string $fromFlags;

    #[SPL\IsWriteOnly()]
    public ?string $fromAttribute;
}