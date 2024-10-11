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

namespace Splash\Metadata\DataTransformer;

use Splash\Metadata\Interfaces\FieldDataTransformer;

/**
 * Base String Fields Transformer
 */
abstract class AbstractStringTransformer implements FieldDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(object $subject, mixed $value): ?string
    {
        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(object $subject, mixed $value): ?string
    {
        return $this->transform($subject, $value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(object $subject, mixed $current, mixed $new): bool
    {
        return ($current == $new);
    }
}
