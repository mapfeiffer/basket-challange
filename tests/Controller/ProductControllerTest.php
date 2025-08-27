<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected mixed $databaseTool;

    private function getApiPath(): string
    {
        $container = static::getContainer();
        return $container->getParameter('app.api_prefix').'/'.
            $container->getParameter('app.api_version').'/products/';
    }

    public function testProductsIndex(): void
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
        self::assertArrayHasKey('0', $data);
        self::assertSame(20, count($data));
        self::assertIsString($data[0]['name']);
        self::assertIsString($data[0]['description']);
        self::assertIsInt($data[0]['price']);
    }

    #[Depends('testProductsIndex')]
    public function testProductsPut(): void
    {
        $jsonBody = json_encode([
            'name' => 'test',
            'description' => 'test',
            'price' => 100,
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
    }

    #[Depends('testProductsPut')]
    public function testProductsUpdate(): void
    {
        $jsonBody = json_encode([
            'name' => 'test',
            'description' => 'test',
            'price' => 100,
        ]);

        $client = static::createClient();
        $client->request(
            'PUT',
            $this->getApiPath(). '1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonBody
        );

        self::assertResponseRedirects($this->getApiPath(). '1');
    }

    #[Depends('testProductsUpdate')]
    public function testProductsDelete(): void
    {
        $jsonBody = json_encode([
            'name' => 'test',
            'description' => 'test',
            'price' => 100,
        ]);

        $client = static::createClient();
        $client->request(
            'DELETE',
            $this->getApiPath(). '1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonBody
        );

        self::assertResponseIsSuccessful();
    }

    #[Depends('testProductsDelete')]
    public function testProductsTryToAccessDeleted(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->getApiPath(). '1',
        );

        self::assertResponseStatusCodeSame(404);
    }
}
