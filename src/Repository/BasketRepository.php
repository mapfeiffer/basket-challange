<?php

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
                    'total_price' => $basketItemTotalPrice,
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

    public function getBasketWithRelationsAsArray(Basket $basket): array
    {
        $products = [];
        $totalPrice = 0;
        foreach ($basket->getBasketItems() as $basketItem) {
            $basketItemTotalPrice = $basketItem->getProduct()->getPrice() * $basketItem->getQuantity();
            $products[] = [
                'product' => $basketItem->getProduct(),
                'quantity' => $basketItem->getQuantity(),
                'total_price' => $basketItemTotalPrice,
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
