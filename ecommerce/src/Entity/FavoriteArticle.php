<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteArticleRepository")
 */
class FavoriteArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FavoriteList", inversedBy="favoriteArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $favoriteList;

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
}
