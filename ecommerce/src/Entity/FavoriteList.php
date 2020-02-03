<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteListRepository")
 */
class FavoriteList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="favoriteList", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FavoriteArticle", mappedBy="favoriteList", orphanRemoval=true)
     */
    private $favoriteArticles;

    public function __construct()
    {
        $this->favoriteArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|FavoriteArticle[]
     */
    public function getFavoriteArticles(): Collection
    {
        return $this->favoriteArticles;
    }

    public function addFavoriteArticle(FavoriteArticle $favoriteArticle): self
    {
        if (!$this->favoriteArticles->contains($favoriteArticle)) {
            $this->favoriteArticles[] = $favoriteArticle;
            $favoriteArticle->setFavoriteList($this);
        }

        return $this;
    }

    public function removeFavoriteArticle(FavoriteArticle $favoriteArticle): self
    {
        if ($this->favoriteArticles->contains($favoriteArticle)) {
            $this->favoriteArticles->removeElement($favoriteArticle);
            // set the owning side to null (unless already changed)
            if ($favoriteArticle->getFavoriteList() === $this) {
                $favoriteArticle->setFavoriteList(null);
            }
        }

        return $this;
    }
}
