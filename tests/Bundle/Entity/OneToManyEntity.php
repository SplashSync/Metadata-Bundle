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

namespace Splash\Metadata\Test\Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Metadata\Attributes as SPL;

/**
 * Doctrine Entity for testing OneToMany Relations
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_one_to_many")]
#[SPL\SplashObject(
    name: "OneToMany",
    ico: "fa fa-list"
)]
class OneToManyEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    /**
     * Required Field with Direct Access
     */
    #[ORM\Column(type: Types::STRING)]
    #[SPL\Field(desc: "Parent Name")]
    public ?string $varchar;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: ChildEntity::class, cascade: array("all"), orphanRemoval: true)]
    #[SPL\Accessor(factory: "createChildren", remover: "removeChildren")]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Children Collection
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    /**
     * Add Children Entity
     */
    public function addChildren(ChildEntity $child): static
    {
        $child->parent = $this;
        $this->children->add($child);

        return $this;
    }

    /**
     * Remove Children Entity
     */
    public function removeChildren(ChildEntity $child): static
    {
        $this->children->removeElement($child);
        $child->parent = null;

        return $this;
    }

    /**
     * Custom Getter for Field Accessor
     */
    public function createChildren(): ?ChildEntity
    {
        return new ChildEntity();
    }
}
