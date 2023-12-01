<?php

namespace Splash\Metadata\Test\Bundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Splash\Metadata\Attributes as SPL;

/**
 * Just a Simple Doctrine Entity for testing Splash Attributes Parsing
 */
#[ORM\Entity()]
#[ORM\Table("spl_doctrine_simple")]
#[SPL\SplashObject(
    name: "SuperSimple",
    ico: "fa fa-user"
)]
class SimpleEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect ReadOnly from Splash Attributes")]
    #[SPL\IsReadOnly()]
    public ?string $readOnly;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect WriteOnly from Splash Attributes")]
    #[SPL\IsWriteOnly()]
    public ?string $writeOnly;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Detect Microdata from Splash Attributes")]
    #[SPL\Flags(listed: true, searchable: true)]
    #[SPL\Microdata(itemType: "https://schema.org/Product", itemProp: "mpn")]
    public ?string $tagged;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[SPL\Field(desc: "Access to Field Using Accessors")]
    #[SPL\Flags(listed: true)]
    #[SPL\Accessor(getter: "accessorsGetter", setter: "accessorsSetter")]
    private ?string $accessors;

    public function __construct(
        #[SPL\Field(desc: "Detect ReadOnly from Php Reflection")]
        public readonly string $phpReadOnly  = "Read Only Value"
    ) {
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