<?php

namespace Splash\Metadata\DataTransformer;

use Splash\Metadata\Interfaces\SplashDataTransformer;

/**
 * Integer Fields Transformer
 */
class IntegerTransformer implements SplashDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): ?int
    {
        return is_scalar($value) ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): ?int
    {
        return $this->transform($value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(mixed $current, mixed $new): bool
    {
        return ($current == $new);
    }
}