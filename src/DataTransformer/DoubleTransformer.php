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
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Integer Fields Transformer
 */
#[AutoconfigureTag(FieldDataTransformer::TAG)]
class DoubleTransformer implements FieldDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(object $subject, mixed $value): ?float
    {
        return is_scalar($value) ? (float) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(object $subject, mixed $value): ?float
    {
        return $this->transform($subject, $value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(object $subject, mixed $current, mixed $new): bool
    {
        if (is_scalar($current) && is_scalar($new)) {
            return (abs(((float) $current) - ((float) $new)) < 1E-6);
        }

        return false;
    }
}
