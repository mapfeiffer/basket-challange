<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // @phpstan-ignore property.unusedType
    private ?int $id = null;

    /**
     * @var Collection<int, BasketItem>
     */
    #[ORM\OneToMany(targetEntity: BasketItem::class, mappedBy: 'basket')]
    private Collection $basketItems;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BasketItem>
     */
    public function getBasketItems(): Collection
    {
        return $this->basketItems;
    }

    /**
     * @param Collection<int, BasketItem> $basketItems
     */
    public function setBasketItems(Collection $basketItems): self
    {
        $this->basketItems = $basketItems;

        return $this;
    }
}
