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
 * Boolean Fields Transformer
 */
#[AutoconfigureTag(FieldDataTransformer::TAG)]
class BooleanTransformer implements FieldDataTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(object $subject, mixed $value): bool
    {
        return !empty($value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(object $subject, mixed $value): bool
    {
        if (!is_scalar($value)) {
            return false;
        }

        return !empty($value);
    }

    /**
     * @inheritDoc
     */
    public function isSame(object $subject, mixed $current, mixed $new): bool
    {
        return ($current == $new);
    }
}
