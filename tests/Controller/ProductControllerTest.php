<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\Depends;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    private ?string $apiPath = null;

    public function testProductsIndex(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $this->apiPath = $container->getParameter('app.api_prefix').'/'.$container->getParameter('app.api_version').'/products/';

        $client->request('GET', $this->apiPath);

        self::assertResponseIsSuccessful();
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
        $response = $client->request(
            'PUT',
            $this->apiPath,
            [],
            [],
            [],
            $jsonBody
        );

        self::assertResponseIsSuccessful();
    }
}
