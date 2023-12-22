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

namespace Splash\Metadata\Services;

use Exception;
use Splash\Client\Splash;
use Splash\Metadata\Interfaces\FieldDataTransformer;
use Splash\Metadata\Mapping\FieldMetadata;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class PropertyTransformer
{
    /**
     * @param FieldDataTransformer[] $dataTransformers
     */
    public function __construct(
        #[TaggedIterator(FieldDataTransformer::TAG)]
        private readonly iterable $dataTransformers,
    ) {
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * @param object        $subject  The Subject of the original representation
     * @param FieldMetadata $metadata Field Metadata Descriptor
     * @param mixed         $value    The value in the original representation
     *
     * @return null|array|bool|float|int|string
     */
    public function transform(object $subject, FieldMetadata $metadata, mixed $value): array|float|bool|int|string|null
    {
        //====================================================================//
        // Apply Data Transformer to Property
        if ($transformer = $this->getTransformer($metadata)) {
            try {
                return $transformer->transform($subject, $value);
            } catch (Exception $e) {
                Splash::log()->report($e);

                return null;
            }
        }

        //====================================================================//
        // Safety Check => Property is Already in a Suitable Format
        return $this->validateProperty($value);
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * @param object        $subject  The Subject of the original representation
     * @param FieldMetadata $metadata Field Metadata Descriptor
     * @param mixed         $value    The value in the transformed representation
     *
     * @return mixed
     */
    public function reverseTransform(object $subject, FieldMetadata $metadata, mixed $value): mixed
    {
        if ($transformer = $this->getTransformer($metadata)) {
            try {
                return $transformer->reverseTransform($subject, $value);
            } catch (Exception $e) {
                Splash::log()->report($e);

                return null;
            }
        }

        return $value;
    }

    /**
     * Compare Original & New Value when Writing
     *
     * @param object        $subject  The Subject of the original representation
     * @param FieldMetadata $metadata Field Metadata Descriptor
     * @param mixed         $current  The current value on Local System
     * @param mixed         $new      The transformed value to write
     *
     * @return bool Current & New are Similar Values
     */
    public function isSame(object $subject, FieldMetadata $metadata, mixed $current, mixed $new): bool
    {
        if ($transformer = $this->getTransformer($metadata)) {
            try {
                return $transformer->isSame($subject, $current, $new);
            } catch (Exception $e) {
                Splash::log()->report($e);

                return false;
            }
        }

        return ($current == $new);
    }

    /**
     * Identify Data Transformer using Field Metadata
     */
    private function getTransformer(FieldMetadata $metadata): ?FieldDataTransformer
    {
        if (!$transformerClass = $metadata->getDataTransformer()) {
            return null;
        }

        foreach ($this->dataTransformers as $transformer) {
            if ($transformer instanceof $transformerClass) {
                return $transformer;
            }
        }

        return null;
    }

    /**
     * Identify Data Transformer using Field Metadata
     */
    private function validateProperty(mixed $value): array|float|bool|int|string|null
    {
        if (is_null($value) || is_scalar($value) || is_array($value)) {
            return $value;
        }
        if ($value instanceof \ArrayAccess) {
            return (array) $value;
        }

        return null;
    }
}
