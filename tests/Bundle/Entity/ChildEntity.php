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
 * Small Child Entity for Testing Relations
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_child")]
class ChildEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OneToManyEntity::class, inversedBy: 'children')]
    public ?OneToManyEntity $parent = null;

    /**
     * Simple Direct Access Varchar
     */
    #[ORM\Column(type: Types::STRING)]
    #[SPL\Field(type: SPL_T_VARCHAR, desc: "This is a Simple Varchar !!")]
    public string $varchar;

    /**
     * Simple Direct Access Integer
     */
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $integer = null;

    /**
     * Simple Direct Access Float
     */
    #[ORM\Column(type: Types::FLOAT, precision: 6, nullable: true)]
    public ?float $float = null;

    /**
     * Simple Direct Access Bool
     */
    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    public ?bool $bool = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
