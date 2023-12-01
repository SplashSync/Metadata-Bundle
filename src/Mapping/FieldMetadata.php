<?php

namespace Splash\Metadata\Mapping;

use Splash\Metadata\DataTransformer;
use Splash\Models\Fields\ObjectField;
use Splash\Metadata\Interfaces\SplashDataTransformer;

class FieldMetadata extends ObjectField
{
    /**
     * This is Identifier Field
     */
    private bool $objectIdentifier = false;

    /**
     * This Field is Searchable in Lists
     */
    private bool $searchable = false;

    /**
     * Excluded => Not Present in Fields List
     */
    private bool $excluded = false;

    /**
     * Data Transformer Used for Data Convert
     *
     * transform => From Local Value to Splash
     * reverseTransform => From Splash Value to Local
     */
    private SplashDataTransformer $dataTransformer;

    /**
     * Function to use as Getter
     */
    private ?string $getter = null;

    /**
     * Function to use as Setter
     */
    private ?string $setter = null;

    public function __construct(string $type = SPL_T_VARCHAR)
    {
        parent::__construct($type);
    }

    //====================================================================//
    // BASIC GETTER & SETTER
    //====================================================================//


    /**
     * Set Field as Object Identifier
     */
    public function setObjectIdentifier(?bool $identifier): static
    {
        if (isset($identifier)) {
            $this->objectIdentifier = $identifier;
        }
        if ($identifier) {
            $this
                ->setExcluded(true)
                ->setIsListed(true)
            ;
        }

        return $this;
    }

    /**
     * This is Object Identifier Field
     */
    public function isObjectIdentifier(): bool
    {
        return $this->objectIdentifier;
    }


    /**
     * Mark this Field as Searchable in Lists
     */
    public function setSearchable(?bool $searchable): static
    {
        if (isset($searchable)) {
            $this->searchable = $searchable;
        }

        return $this;
    }

    /**
     * This Field is Searchable in Lists
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Excluded => Not Present in Fields List
     */
    public function setExcluded(?bool $excluded): static
    {
        if (isset($excluded)) {
            $this->excluded = $excluded;
        }

        return $this;
    }

    /**
     * Excluded => Not Present in Fields List
     */
    public function isExcluded(): bool
    {
        return $this->excluded;
    }

    /**
     * Set Data Transformer Used for Data Convert
     */
    public function setDataTransformer(SplashDataTransformer $dataTransformer): static
    {
        $this->dataTransformer = $dataTransformer;

        return $this;
    }

    /**
     * Get Data Transformer Used for Data Convert
     */
    public function getDataTransformer(): ?SplashDataTransformer
    {
        return $this->dataTransformer ?? $this->detectDataTransformer();
    }

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

    //====================================================================//
    // PRIVATE METHODS
    //====================================================================//

    /**
     * Autodetect Data Transformer for field
     */
    public function detectDataTransformer(): ?SplashDataTransformer
    {
        return match($this->type) {
            SPL_T_VARCHAR, SPL_T_TEXT => new DataTransformer\VarcharTransformer(),
            SPL_T_BOOL => new DataTransformer\BooleanTransformer(),
            SPL_T_INT => new DataTransformer\IntegerTransformer(),
            SPL_T_DOUBLE => new DataTransformer\DoubleTransformer(),

            default => null,
        };
    }


}