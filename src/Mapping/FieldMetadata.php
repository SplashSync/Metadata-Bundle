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

namespace Splash\Metadata\Mapping;

use Splash\Components\FieldsManager;
use Splash\Metadata\Models\Metadata\AccessorTrait;
use Splash\Metadata\Models\Metadata\ManualAccessTrait;
use Splash\Metadata\Models\Metadata\ParentTrait;
use Splash\Models\Fields\ObjectField;

/**
 * Splash Metadata Parser - Field Information
 */
class FieldMetadata extends ObjectField
{
    use AccessorTrait;
    use ManualAccessTrait;
    use ParentTrait;

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

    public function __construct(string $type = SPL_T_VARCHAR)
    {
        parent::__construct($type);
    }

    //====================================================================//
    // BASIC GETTER & SETTER
    //====================================================================//

    /**
     * @inheritdoc
     */
    public function getFieldId(): string
    {
        return $this->getChildId() ?? $this->id;
    }

    public function isListField(): bool
    {
        return !empty(FieldsManager::isListField($this->type));
    }

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
}
