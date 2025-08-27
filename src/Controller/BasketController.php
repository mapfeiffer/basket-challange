<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use JetBrains\PhpStorm\NoReturn;
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
        return $this->json($entityManager->getRepository(Basket::class)->getAllBasketsWithRelationsAsArray(), Response::HTTP_OK);
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

        foreach ($products['products'] as $product) {
            $basketItem = new BasketItem();
            $basketItem->setProduct($entityManager->getRepository(Product::class)->find($product['product_id']));
            $basketItem->setQuantity($product['quantity']);
            $basketItem->setBasket($basket);
            $entityManager->persist($basketItem);
        }

        $entityManager->flush();

        return $this->json($entityManager->getRepository(Basket::class)->getBasketWithRelationsAsArray($basket), Response::HTTP_OK);
    }

    #[Route('%app.api_prefix%/%app.api_version%/baskets/{id}', name: 'api_%app.api_version%_basket_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, Basket $basket): JsonResponse
    {
        return $this->json($entityManager->getRepository(Basket::class)->getBasketWithRelationsAsArray($basket), Response::HTTP_OK);
    }

    #[NoReturn]
    #[Route('%app.api_prefix%/%app.api_version%/baskets/{id}', name: 'api_%app.api_version%_basket_update', methods: ['PUT'], format: 'json')]
    public function update(
        EntityManagerInterface $entityManager,
        Basket $basket,
        Request $request,
    ): Response {
        // Remove all basket items from basket
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $basketItem) {
            $entityManager->remove($basketItem);
        }

        $products = json_decode($request->getContent(), true);

        foreach ($products['products'] as $product) {
            $basketItem = new BasketItem();
            $basketItem->setProduct($entityManager->getRepository(Product::class)->find($product['product_id']));
            $basketItem->setQuantity($product['quantity']);
            $basketItem->setBasket($basket);
            $entityManager->persist($basketItem);
        }

        $entityManager->flush();

        return $this->redirectToRoute('api_%app.api_version%_basket_show', [
            'id' => $basket->getId(),
        ]);
    }

    #[Route('%app.api_prefix%/%app.api_version%/baskets/{id}', name: 'api_%app.api_version%_basket_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Basket $basket): Response
    {
        $entityManager->remove($basket);
        $entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
