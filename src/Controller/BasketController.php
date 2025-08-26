<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BasketController extends AbstractController
{
    #[Route('%app.api_prefix%/%app.api_version%/baskets/', name: 'api_%app.api_version%_basket', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json($entityManager->getRepository(Basket::class)->findAllAsArray(), Response::HTTP_OK);
    }

    /**
     * @throws ORMException
     */
    #[Route('%app.api_prefix%/%app.api_version%/baskets/', name: 'api_%app.api_version%_basket_create', methods: ['PUT', 'POST'], format: 'json')]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $basket = new Basket();
        $entityManager->persist($basket);
        $entityManager->flush();
        $entityManager->refresh($basket);

        $products = json_decode($request->getContent(), true);

        foreach ($products['pruducts'] as $product) {
            $basketItem = new BasketItem();
            $basketItem->setProductId($product['product_id']);
            $basketItem->setQuantity($product['quantity']);
            $basketItem->setBasketId($basket->getId());
            $entityManager->persist($basketItem);
        }

        $entityManager->flush($basket);

        return $this->json([
            'id' => $basket->getId(),
            'createdAt' => $basket->getCreatedAt(),
            'updatedAt' => $basket->getUpdatedAt(),
        ], Response::HTTP_OK);
    }
}
