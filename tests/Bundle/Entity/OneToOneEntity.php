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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Metadata\Attributes as SPL;

/**
 * Doctrine Entity for testing OneToOne Relations
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_one_to_one")]
#[SPL\SplashObject(
    name: "OneToOne",
    ico: "fa fa-link"
)]
class OneToOneEntity
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

    #[ORM\OneToOne(targetEntity: ChildEntity::class, cascade: array("all"))]
    #[SPL\Accessor(factory: "addChild")]
    private ?ChildEntity $child = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Child Entity
     */
    public function getChild(): ?ChildEntity
    {
        return $this->child;
    }

    /**
     * Set Child Entity
     */
    public function setChild(?ChildEntity $child): static
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Add a Child Object
     */
    public function addChild(): ?ChildEntity
    {
        if (!isset($this->child)) {
            $this->child = new ChildEntity();
        }

        return $this->child;
    }
}
