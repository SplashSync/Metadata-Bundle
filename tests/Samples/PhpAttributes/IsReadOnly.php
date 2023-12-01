<?php

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
    public ?string $fromFlags;

    #[SPL\IsReadOnly()]
    public ?string $fromAttribute;

    #[SPL\Field()]
    public readonly ?string $fromPhp;
}