<?php

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