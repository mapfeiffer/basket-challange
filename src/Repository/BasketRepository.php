<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Basket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Basket>
 */
class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    /**
     * @return array<int, array{id: int|null, products: array<int, array{product: \App\Entity\Product, quantity: int, totalProductPrice: int}>, totalPrice: int}>
     */
    public function getAllBasketsWithRelationsAsArray(): array
    {
        $baskets = $this->findAll();
        $basketsArray = [];

        foreach ($baskets as $basket) {
            $products = [];
            $totalPrice = 0;
            foreach ($basket->getBasketItems() as $basketItem) {
                $basketItemTotalPrice = $basketItem->getProduct()->getPrice() * $basketItem->getQuantity();
                $products[] = [
                    'product' => $basketItem->getProduct(),
                    'quantity' => $basketItem->getQuantity(),
                    'totalProductPrice' => $basketItemTotalPrice,
                ];
                $totalPrice = $totalPrice + $basketItemTotalPrice;
            }

            $basketsArray[] = [
                'id' => $basket->getId(),
                'products' => $products,
                'totalPrice' => $totalPrice,
            ];
        }

        return $basketsArray;
    }

    /**
     * @return array{id: int|null, products: array<int, array{product: \App\Entity\Product, quantity: int, totalProductPrice: int}>, totalPrice: int}
     */
    public function getBasketWithRelationsAsArray(Basket $basket): array
    {
        $products = [];
        $totalPrice = 0;
        foreach ($basket->getBasketItems() as $basketItem) {
            $basketItemTotalPrice = $basketItem->getProduct()->getPrice() * $basketItem->getQuantity();
            $products[] = [
                'product' => $basketItem->getProduct(),
                'quantity' => $basketItem->getQuantity(),
                'totalProductPrice' => $basketItemTotalPrice,
            ];
            $totalPrice = $totalPrice + $basketItemTotalPrice;
        }

        return [
            'id' => $basket->getId(),
            'products' => $products,
            'totalPrice' => $totalPrice,
        ];
    }
}
