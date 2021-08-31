<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
    private $designation_of_origin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grape_variety;

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
    private $service_temperature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $to_drink_until;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

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
        return $this->designation_of_origin;
    }

    public function setDesignationOfOrigin(?string $designation_of_origin): self
    {
        $this->designation_of_origin = $designation_of_origin;

        return $this;
    }

    public function getGrapeVariety(): ?string
    {
        return $this->grape_variety;
    }

    public function setGrapeVariety(?string $grape_variety): self
    {
        $this->grape_variety = $grape_variety;

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
        return $this->service_temperature;
    }

    public function setServiceTemperature(?string $service_temperature): self
    {
        $this->service_temperature = $service_temperature;

        return $this;
    }

    public function getToDrinkUntil(): ?int
    {
        return $this->to_drink_until;
    }

    public function setToDrinkUntil(?int $to_drink_until): self
    {
        $this->to_drink_until = $to_drink_until;

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
}
