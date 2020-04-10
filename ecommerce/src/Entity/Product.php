<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $liter;

    /**
     * @ORM\Column(type="integer")
     */
    private $alcool;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likeCounter;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orderCounter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FavoriteProduct", mappedBy="Product", orphanRemoval=true)
     */
    private $favoriteProducts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tags", mappedBy="Product")
     */
    private $tags;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    public function __construct()
    {
        $this->favoriteProducts = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getGrape(): ?string
    {
        return $this->grape;
    }

    public function setGrape(?string $grape): self
    {
        $this->grape = $grape;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getLiter(): ?string
    {
        return $this->liter;
    }

    public function setLiter(string $liter): self
    {
        $this->liter = $liter;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLikeCounter(): ?int
    {
        return $this->likeCounter;
    }

    public function setLikeCounter(?int $likeCounter): self
    {
        $this->likeCounter = $likeCounter;

        return $this;
    }

    public function getOrderCounter(): ?int
    {
        return $this->orderCounter;
    }

    public function setOrderCounter(?int $orderCounter): self
    {
        $this->orderCounter = $orderCounter;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection|FavoriteProduct[]
     */
    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
            $favoriteProduct->setProduct($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts->removeElement($favoriteProduct);
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getProduct() === $this) {
                $favoriteProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addProduct($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeProduct($this);
        }

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

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
}
