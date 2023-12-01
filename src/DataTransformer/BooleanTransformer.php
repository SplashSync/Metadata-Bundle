<?php

namespace Splash\Metadata\DataTransformer;

use Splash\Metadata\Interfaces\SplashDataTransformer;

/**
 * Boolean Fields Transformer
 */
class BooleanTransformer implements SplashDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): bool
    {
        return !empty($value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): bool
    {
        if (!is_scalar($value)) {
            return false;
        }

        return !empty($value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(mixed $current, mixed $new): bool
    {
        return ($current == $new);
    }
}