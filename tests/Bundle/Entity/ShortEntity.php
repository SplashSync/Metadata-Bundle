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

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Metadata\Attributes as SPL;

/**
 * Just a Short Doctrine Entity for very Simple Tests
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_short")]
#[SPL\SplashObject(
    name: "Short",
    ico: "fa fa-compress"
)]
class ShortEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    /**
     * Simple Direct Access Varchar
     */
    #[ORM\Column(type: Types::STRING)]
    #[SPL\Field(type: SPL_T_VARCHAR, desc: "This is a Simple Varchar !!")]
    public string $varchar;

    /**
     * Simple Direct Access Email
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(type: SPL_T_EMAIL, desc: "Type changed to email via PHP Attribute...")]
    public ?string $email;

    /**
     * Simple Direct Access Url
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(type: SPL_T_URL, desc: "Type changed to url via PHP Attribute...")]
    public ?string $url;

    /**
     * Simple Direct Access Select
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(type: SPL_T_VARCHAR, desc: "Choices loaded via PHP Attribute...")]
    #[SPL\Choices(array("A" => "Value A", "B" => "Value B", "C" => "Value C"))]
    public ?string $choice;

    /**
     * Simple Direct Access Text
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $text = null;

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

    /**
     * Simple Direct Access Date
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[SPL\Field(desc: "Simple Date Field")]
    public ?DateTime $date = null;

    /**
     * Simple Direct Access Datetime
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[SPL\Field(desc: "Complete Date Field")]
    public ?DateTime $dateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
