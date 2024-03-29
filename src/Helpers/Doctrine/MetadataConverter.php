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

namespace Splash\Metadata\Helpers\Doctrine;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class MetadataConverter
{
    /**
     * Check if Field is Object Identifier
     */
    public static function isIdentifier(array $fieldMapping, ClassMetadataInfo $classMetadata = null): ?bool
    {
        return !empty($fieldMapping['id'] ?? false) ? !$classMetadata : null;
    }

    /**
     * Check if Field is Excluded
     */
    public static function isExcluded(array $fieldMapping): ?bool
    {
        return self::isIdentifier($fieldMapping);
    }

    /**
     * Check if Field is Required
     */
    public static function isRequired(array $fieldMapping): bool
    {
        return (
            empty($fieldMapping['nullable'] ?? true)
            || !empty($fieldMapping['generated'] ?? false)
        )
            && empty($fieldMapping['id'] ?? false)
        ;
    }

    /**
     * Check if Field is Read Only
     */
    public static function isReadOnly(array $fieldMapping, ClassMetadataInfo $classMetadata = null): bool
    {
        return !empty($fieldMapping['generated'] ?? false)
            || !empty($fieldMapping['id'] ?? false)
            || ($classMetadata && $classMetadata->isReadOnly)
        ;
    }

    /**
     * Check if Field is Primary
     */
    public static function isPrimary(array $fieldMapping): bool
    {
        return $fieldMapping['unique'] ?? false;
    }

    public static function toSplashType(string $docFieldType): ?string
    {
        return match($docFieldType) {
            //==============================================================================
            // Basic Fields
            Types::STRING, Types::GUID => SPL_T_VARCHAR,
            Types::TEXT => SPL_T_TEXT,
            Types::BOOLEAN => SPL_T_BOOL,
            Types::INTEGER, Types::BIGINT => SPL_T_INT,
            Types::FLOAT => SPL_T_DOUBLE,
            //==============================================================================
            // Date Fields
            Types::DATE_MUTABLE, Types::DATE_IMMUTABLE => SPL_T_DATE,
            Types::DATETIME_MUTABLE, Types::DATETIME_IMMUTABLE => SPL_T_DATETIME,
            Types::DATETIMETZ_MUTABLE, Types::DATETIMETZ_IMMUTABLE => SPL_T_DATETIME,

            default => null,
        };
    }
}
