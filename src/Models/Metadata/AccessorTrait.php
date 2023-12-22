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

namespace Splash\Metadata\Models\Metadata;

use Splash\Components\FieldsManager;
use Splash\Metadata\DataTransformer;

trait AccessorTrait
{
    //====================================================================//
    // BASIC ACCESS METHODS
    //====================================================================//

    /**
     * Function to use as Property Getter
     */
    private ?string $getter = null;

    /**
     * Function to use as Property Setter
     */
    private ?string $setter = null;

    //====================================================================//
    // CHILDREN ACCESS METHODS
    //====================================================================//

    /**
     * Function to use as Child Factory
     */
    private ?string $factory = null;

    /**
     * Function to use as Child Adder
     */
    private ?string $adder = null;

    /**
     * Function to use as Child Remover
     */
    private ?string $remover = null;

    /**
     * Data Transformer Used for Data Convert
     *
     * transform => From Local Value to Splash
     * reverseTransform => From Splash Value to Local
     *
     * @var null|class-string
     */
    private ?string $dataTransformer = null;

    public function getGetter(): ?string
    {
        return $this->getter;
    }

    public function setGetter(?string $getter): static
    {
        $this->getter = $getter;

        return $this;
    }

    public function getSetter(): ?string
    {
        return $this->setter;
    }

    public function setSetter(?string $setter): static
    {
        $this->setter = $setter;

        return $this;
    }

    public function getFactory(): ?string
    {
        return $this->factory;
    }

    public function setFactory(?string $factory): static
    {
        $this->factory = $factory;

        return $this;
    }

    public function getAdder(): ?string
    {
        return $this->adder;
    }

    public function setAdder(?string $adder): static
    {
        $this->adder = $adder;

        return $this;
    }

    public function getRemover(): ?string
    {
        return $this->remover;
    }

    public function setRemover(?string $remover): static
    {
        $this->remover = $remover;

        return $this;
    }

    /**
     * Set Data Transformer Used for Data Convert
     *
     * @param null|class-string $dataTransformer
     */
    public function setDataTransformer(?string $dataTransformer): static
    {
        $this->dataTransformer = $dataTransformer;

        return $this;
    }

    /**
     * Get Data Transformer Used for Data Convert
     */
    public function getDataTransformer(): ?string
    {
        return $this->dataTransformer ?? $this->detectDataTransformer();
    }

    //====================================================================//
    // PRIVATE METHODS
    //====================================================================//

    /**
     * Autodetect Data Transformer for field
     */
    private function detectDataTransformer(): ?string
    {
        //====================================================================//
        // Detect List Fields
        $type = FieldsManager::fieldName($this->type) ?? $this->type;

        //====================================================================//
        // Detect Generic Data Transformer
        return match ($type) {
            SPL_T_VARCHAR, SPL_T_TEXT, SPL_T_EMAIL, SPL_T_URL => DataTransformer\VarcharTransformer::class,
            SPL_T_BOOL => DataTransformer\BooleanTransformer::class,
            SPL_T_INT => DataTransformer\IntegerTransformer::class,
            SPL_T_DOUBLE => DataTransformer\DoubleTransformer::class,
            SPL_T_DATE => DataTransformer\DateTransformer::class,
            SPL_T_DATETIME => DataTransformer\DatetimeTransformer::class,

            default => null,
        };
    }
}
