<?php

namespace Splash\Metadata\Interfaces;

use Exception;
use Symfony\Component\Form\DataTransformerInterface;

interface SplashDataTransformer extends DataTransformerInterface
{
    /**
     * Compare Original & New Value when Writing
     *
     * @param mixed $current The current value on Local System
     * @param mixed $new The transformed value to write
     *
     * @return bool Current & New are Similar Values
     */
    public function isSame(mixed $current, mixed $new): bool;
}