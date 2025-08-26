<?php

namespace App\Entity;

use App\Repository\BasketItemRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketItemRepository::class)]
class BasketItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $product_id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'product')]
    private Product $product;

    #[ORM\Column]
    private int $quantity;

    #[ORM\Column]
    private int $basket_id;

    #[ORM\ManyToOne(targetEntity: Basket::class, inversedBy: 'basket')]
    private Basket $basket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBasketId(): int
    {
        return $this->basket_id;
    }

    public function setBasketId(int $basketId): static
    {
        $this->basket_id = $basketId;

        return $this;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
