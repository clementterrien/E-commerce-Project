<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteProductRepository")
 */
class FavoriteProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FavoriteList", inversedBy="favoriteProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $favoriteList;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="favoriteProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavoriteList(): ?FavoriteList
    {
        return $this->favoriteList;
    }

    public function setFavoriteList(?FavoriteList $favoriteList): self
    {
        $this->favoriteList = $favoriteList;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }
}
