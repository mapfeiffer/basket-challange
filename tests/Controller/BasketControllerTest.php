<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BasketControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected mixed $databaseTool;

    private function getApiPath(): string
    {
        $container = static::getContainer();

        return $container->getParameter('app.api_prefix').'/'.
            $container->getParameter('app.api_version').'/baskets/';
    }

    public function testBasketsIndex(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        // Fixtures
        $databaseTool = $container->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([AppFixtures::class]);

        $client->request('GET', $this->getApiPath());

        self::assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        self::assertIsArray($data);
        self::assertSame(0, count($data));
    }

    #[Depends('testBasketsIndex')]
    public function testBasketsPut(): void
    {
        $jsonBody = json_encode([
            'products' => [
                [
                    'product_id' => 7,
                    'quantity' => 1,
                ],
                [
                    'product_id' => 12,
                    'quantity' => 3,
                ],
            ],
        ]);

        $client = static::createClient();
        $client->request(
            'PUT',
            $this->getApiPath(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonBody
        );

        self::assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        self::assertIsArray($data);
        // Basket has id, products and totalPrice
        self::assertSame(3, count($data));
        self::assertSame(1, $data['id']);
        self::assertSame(2, count($data['products']));
        self::assertIsInt($data['totalPrice']);

        // Test basket product price calculations
        foreach ($data['products'] as $product) {
            $totalProductPrice = $product['quantity'] * $product['product']['price'];
            self::assertSame($product['totalProductPrice'], $totalProductPrice);
        }

        // Test basket price calculations
        $totalPrice = 0;
        foreach ($data['products'] as $product) {
            $totalPrice = $totalPrice + $product['totalProductPrice'];
        }
        self::assertSame($data['totalPrice'], $totalPrice);
    }

    #[Depends('testBasketsPut')]
    public function testBasketsUpdate(): void
    {
        $jsonBody = json_encode([
            'products' => [
                [
                    'product_id' => 17,
                    'quantity' => 4,
                ],
                [
                    'product_id' => 10,
                    'quantity' => 7,
                ],
            ],
        ]);

        $client = static::createClient();
        $client->request(
            'PUT',
            $this->getApiPath().'1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonBody
        );

        self::assertResponseRedirects($this->getApiPath().'1');
    }

    #[Depends('testBasketsUpdate')]
    public function testBasketsDelete(): void
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            $this->getApiPath().'1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        self::assertResponseIsSuccessful();
    }

    #[Depends('testBasketsDelete')]
    public function testBasketsTryToAccessDeleted(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->getApiPath().'1',
        );

        self::assertResponseStatusCodeSame(404);
    }
}
