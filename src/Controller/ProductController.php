<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('%app.api_prefix%/%app.api_version%/products/', name: 'api_%app.api_version%_products_list', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json($entityManager->getRepository(Product::class)->findAllAsArray(), Response::HTTP_OK);
    }

    /**
     * @throws ORMException
     */
    #[Route('%app.api_prefix%/%app.api_version%/products/', name: 'api_%app.api_version%_product_create', methods: ['PUT', 'POST'], format: 'json')]
    public function create(EntityManagerInterface $entityManager, #[MapRequestPayload] Product $product): JsonResponse
    {
        $entityManager->persist($product);
        $entityManager->flush();

        $entityManager->refresh($product);

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'createdAt' => $product->getCreatedAt(),
            'updatedAt' => $product->getUpdatedAt(),
        ], Response::HTTP_OK);
    }

    #[Route('%app.api_prefix%/%app.api_version%/products/{id}', name: 'api_%app.api_version%_product_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'createdAt' => $product->getCreatedAt(),
            'updatedAt' => $product->getUpdatedAt(),
        ], Response::HTTP_OK);
    }

    #[NoReturn]
    #[Route('%app.api_prefix%/%app.api_version%/products/{id}', name: 'api_%app.api_version%_product_update', methods: ['PUT'], format: 'json')]
    public function update(
        EntityManagerInterface $entityManager,
        Product $product,
        #[MapRequestPayload] Product $updatedProduct,
    ): Response {
        $product->setName($updatedProduct->getName());
        $product->setDescription($updatedProduct->getDescription());
        $product->setPrice($updatedProduct->getPrice());
        $entityManager->flush();

        return $this->redirectToRoute('api_%app.api_version%_product_show', [
            'id' => $product->getId(),
        ]);
    }

    #[Route('%app.api_prefix%/%app.api_version%/products/{id}', name: 'api_%app.api_version%_product_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Product $product): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
