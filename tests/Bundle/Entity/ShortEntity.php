<?php

namespace Splash\Metadata\Test\Bundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Splash\Metadata\Attributes as SPL;

/**
 * Just a Short Doctrine Entity for very Simple Tests
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_short")]
class ShortEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

//    #[ORM\Column(type: Types::STRING, unique: true)]
//    public string $primary;

    #[ORM\Column(type: Types::STRING)]
    #[SPL\Field(type: SPL_T_URL, desc: "This is a Simple Varchar !!")]
    public string $varchar;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(type: SPL_T_EMAIL, desc: "Type changed to email via PHP Attribute...")]
    public ?string $email;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(type: SPL_T_URL, desc: "Type changed to url via PHP Attribute...")]
    public ?string $url;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $integer = null;

    #[ORM\Column(type: Types::FLOAT, precision: 6, nullable: true)]
    public ?float $float = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    public ?bool $bool = null;
//
//    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
//    public DateTime $updatedAt;
}