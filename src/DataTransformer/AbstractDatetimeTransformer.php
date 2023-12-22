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

use DateTime;
use Exception;
use Splash\Metadata\Interfaces\FieldDataTransformer;
use Splash\Metadata\Interfaces\TimezoneAware;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Datetime Fields Transformer
 */
#[AutoconfigureTag(FieldDataTransformer::TAG)]
abstract class AbstractDatetimeTransformer implements FieldDataTransformer
{
    protected string $format = SPL_T_DATETIMECAST;

    /**
     * @inheritDoc
     */
    public function transform(object $subject, mixed $value): ?string
    {
        //====================================================================//
        // DateTime received
        if ($value instanceof DateTime) {
            return $this->format($subject, $value);
        }
        //====================================================================//
        // Date String received
        if (!$value || !is_scalar($value)) {
            return null;
        }

        try {
            return $this->format($subject, new DateTime((string) $value));
        } catch (\Exception $ex) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(object $subject, mixed $value): ?DateTime
    {
        //====================================================================//
        // Safety Check Date String received
        if (!$value || !is_scalar($value)) {
            return null;
        }

        //====================================================================//
        // Create DateTime with Object Timezone Detection
        try {
            return ($subject instanceof TimezoneAware)
                ? new DateTime((string) $value, $subject->getTimezone())
                : new DateTime((string) $value)
            ;
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function isSame(object $subject, mixed $current, mixed $new, string $format = null): bool
    {
        $format ??= $this->format;
        if (($current instanceof DateTime) && ($new instanceof DateTime)) {
            return $current->format($format) == $new->format($format);
        }

        return (empty($current) && empty($new));
    }

    /**
     * Format DateTime using Timezone Detection
     */
    public function format(object $subject, DateTime $value, string $format = null): string
    {
        //====================================================================//
        // Force Timezone to Object Time Timezone
        if ($subject instanceof TimezoneAware) {
            $value->setTimezone($subject->getTimezone());
        }

        //====================================================================//
        // Format Date
        return $value->format($format ?? $this->format);
    }
}
