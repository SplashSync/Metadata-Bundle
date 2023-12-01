<?php

namespace Splash\Metadata\DataTransformer;

use Splash\Metadata\Interfaces\SplashDataTransformer;

/**
 * Boolean Fields Transformer
 */
class VarcharTransformer implements SplashDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): ?string
    {
        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): ?string
    {
        return $this->transform($value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(mixed $current, mixed $new): bool
    {
        return ($current === $new);
    }
}