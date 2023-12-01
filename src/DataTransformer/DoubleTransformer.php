<?php

namespace Splash\Metadata\DataTransformer;

use Splash\Metadata\Interfaces\SplashDataTransformer;

/**
 * Integer Fields Transformer
 */
class DoubleTransformer implements SplashDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): ?float
    {
        return is_scalar($value) ? (float) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): ?float
    {
        return $this->transform($value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(mixed $current, mixed $new): bool
    {
        if (is_scalar($current) && is_scalar($new)) {
            return (abs(((float) $current) -((float) $new)) < 1E-6);
        }

        return false;
    }
}