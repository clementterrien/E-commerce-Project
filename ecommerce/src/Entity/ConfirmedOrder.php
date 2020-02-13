<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfirmedOrderRepository")
 */
class ConfirmedOrder
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
    private $Cart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adress", inversedBy="confirmedOrders", cascade={"persist", "remove"})
     */
    private $Adress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="confirmedOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $TotalPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?string
    {
        return $this->Cart;
    }

    public function setCart(string $Cart): self
    {
        $this->Cart = $Cart;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->Adress;
    }

    public function setAdress(?Adress $Adress): self
    {
        $this->Adress = $Adress;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->TotalPrice;
    }

    public function setTotalPrice(int $TotalPrice): self
    {
        $this->TotalPrice = $TotalPrice;

        return $this;
    }
}
