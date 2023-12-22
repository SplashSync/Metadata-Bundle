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
 * Just a Simple Doctrine Entity for testing Splash Attributes Parsing
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_simple")]
#[SPL\SplashObject(
    name: "Simple",
    ico: "fa fa-vial"
)]
class SimpleEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    /**
     * Required Field with Direct Access
     */
    #[ORM\Column(type: Types::STRING)]
    #[SPL\Field(desc: "Required Field")]
    public ?string $varchar;

    /**
     * Read Only Field with Direct Access
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect ReadOnly from Splash Attributes")]
    #[SPL\IsReadOnly()]
    public ?string $readOnly;

    /**
     * Write Only Field with Direct Access
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect WriteOnly from Splash Attributes")]
    #[SPL\IsWriteOnly()]
    public ?string $writeOnly;

    /**
     * Field with Microdata, Listed & Searchable
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect Microdata from Splash Attributes, Listed & Searchable")]
    #[SPL\Flags(listed: true, searchable: true)]
    #[SPL\Microdata(itemType: "https://schema.org/Product", itemProp: "mpn")]
    public ?string $tagged;

    /**
     * Field with access via custom Accessor functions
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Access to Field Using Accessors")]
    #[SPL\Flags(listed: true)]
    #[SPL\Accessor(getter: "accessorsGetter", setter: "accessorsSetter")]
    private ?string $accessors;

    public function __construct(
        #[SPL\Field(desc: "Detect ReadOnly from Php Reflection")]
        public readonly string $phpReadOnly = "Read Only Value"
    ) {
        $this->readOnly = uniqid("Read_Only_");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Custom Getter for Field Accessor
     */
    public function accessorsGetter(): ?string
    {
        return $this->accessors ?? null;
    }

    /**
     * Custom Setter for Field Accessor
     */
    public function accessorsSetter(?string $accessors): void
    {
        $this->accessors = $accessors;
    }
}
