<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WineRepository;
use Doctrine\ORM\Mapping\AttributeOverrides;

/**
 * @ORM\Entity
 */
class Wine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $wine_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $alcool;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vintage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="float")
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designationOfOrigin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grapeVariety;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tastes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smell;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serviceTemperature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $toDrinkUntil;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=WineInventory::class, mappedBy="wine", cascade={"persist", "remove"})
     */
    private $wineInventory;

    /**
     * @ORM\ManyToMany(targetEntity=WineCategory::class, mappedBy="wines")
     */
    private $wineCategories;

    public function __construct()
    {
        $this->wineCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWineId(): ?int
    {
        return $this->wine_id;
    }

    public function setWineId(int $wine_id): self
    {
        $this->wine_id = $wine_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setProductName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlcool(): ?int
    {
        return $this->alcool;
    }

    public function setAlcool(int $alcool): self
    {
        $this->alcool = $alcool;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getVintage(): ?int
    {
        return $this->vintage;
    }

    public function setVintage(?int $vintage): self
    {
        $this->vintage = $vintage;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(float $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDesignationOfOrigin(): ?string
    {
        return $this->designationOfOrigin;
    }

    public function setDesignationOfOrigin(?string $designationOfOrigin): self
    {
        $this->designationOfOrigin = $designationOfOrigin;

        return $this;
    }

    public function getGrapeVariety(): ?string
    {
        return $this->grapeVariety;
    }

    public function setGrapeVariety(?string $grapeVariety): self
    {
        $this->grapeVariety = $grapeVariety;

        return $this;
    }

    public function getTastes(): ?string
    {
        return $this->tastes;
    }

    public function setTastes(?string $tastes): self
    {
        $this->tastes = $tastes;

        return $this;
    }

    public function getSmell(): ?string
    {
        return $this->smell;
    }

    public function setSmell(?string $smell): self
    {
        $this->smell = $smell;

        return $this;
    }

    public function getServiceTemperature(): ?string
    {
        return $this->serviceTemperature;
    }

    public function setServiceTemperature(?string $serviceTemperature): self
    {
        $this->serviceTemperature = $serviceTemperature;

        return $this;
    }

    public function getToDrinkUntil(): ?int
    {
        return $this->toDrinkUntil;
    }

    public function setToDrinkUntil(?int $toDrinkUntil): self
    {
        $this->toDrinkUntil = $toDrinkUntil;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getWineInventory(): ?WineInventory
    {
        return $this->wineInventory;
    }

    public function setWineInventory(WineInventory $wineInventory): self
    {
        $this->wineInventory = $wineInventory;

        // set the owning side of the relation if necessary
        if ($wineInventory->getWine() !== $this) {
            $wineInventory->setWine($this);
        }

        return $this;
    }

    /**
     * @return Collection|WineCategory[]
     */
    public function getWineCategories(): Collection
    {
        return $this->wineCategories;
    }

    public function addWineCategory(WineCategory $wineCategory): self
    {
        if (!$this->wineCategories->contains($wineCategory)) {
            $this->wineCategories[] = $wineCategory;
            $wineCategory->addWine($this);
        }

        return $this;
    }

    public function removeWineCategory(WineCategory $wineCategory): self
    {
        if ($this->wineCategories->contains($wineCategory)) {
            $this->wineCategories->removeElement($wineCategory);
            $wineCategory->removeWine($this);
        }

        return $this;
    }
}
