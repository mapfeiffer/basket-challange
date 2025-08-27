<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Product;
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
                $products[] = $basketItem->getProduct();
                $totalPrice = $totalPrice + ($basketItem->getProduct()->getPrice() * $basketItem->getQuantity());
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
            $products[] = $basketItem->getProduct();
            $totalPrice = $totalPrice + ($basketItem->getProduct()->getPrice() * $basketItem->getQuantity());
        }

        return [
            'id' => $basket->getId(),
            'products' => $products,
            'totalPrice' => $totalPrice,
        ];
    }

    private function getTotalPrice($basketId): int
    {
        $totalPrice = 0;

        $entityManager = $this->getEntityManager();
        $basketItems = $entityManager->getRepository(BasketItem::class)->findBy(['basket_id' => $basketId]);

        foreach ($basketItems as $basketItem) {
            $product = $entityManager->getRepository(Product::class)->findById($basketItem->getProductId())[0];
            $totalPrice = $totalPrice + ($product->getPrice() * $basketItem->getQuantity());
        }

        return $totalPrice;
    }
}
