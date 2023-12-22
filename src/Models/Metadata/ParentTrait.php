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

use Splash\Metadata\Mapping\FieldMetadata;

/**
 * Manage Parent <> Children Relation for Fields
 */
trait ParentTrait
{
    /**
     * Parent Field Metadata
     */
    private ?FieldMetadata $parent = null;

    /**
     * Children Fields Metadata
     *
     * @var FieldMetadata[]
     */
    private array $children = array();

    /**
     * Child Field Identifier
     */
    private ?string $childId = null;

    /**
     * Child Class Name
     *
     * @var null|class-string
     */
    private ?string $childClass = null;

    /**
     * Set Parent Child Field Relation
     */
    public function setParent(FieldMetadata $parent, string $childClass, string $childId): static
    {
        //====================================================================//
        // Set as Parent
        $this->parent = $parent;
        //====================================================================//
        // Configure Parent Field
        $parent
            ->addChildren($this)
            ->setExcluded(true)
            ->setGroup(ucfirst($parent->getFieldId()))
        ;
        //====================================================================//
        // Configure Child Field
        $this->setGroup(ucfirst($parent->getFieldId()));
        $this->childClass = $childClass;
        $this->childId = $childId;

        return $this;
    }

    /**
     * Get Parent Field
     */
    public function getParent(): ?FieldMetadata
    {
        return $this->parent;
    }

    /**
     * This Field has a Parent Field
     */
    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    /**
     * Add Children Field Relation
     */
    public function addChildren(FieldMetadata $children): static
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Get Children Field
     *
     * @return FieldMetadata[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * This Field has Children Fields
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * Get Child Field ID
     */
    public function getChildId(): ?string
    {
        return $this->childId;
    }

    /**
     * Get Child Object Class
     */
    public function getChildClass(): ?string
    {
        return $this->childClass;
    }
}
