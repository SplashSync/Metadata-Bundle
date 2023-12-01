<?php

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