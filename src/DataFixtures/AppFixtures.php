<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create 20 products
        for ($i = 1; $i < 21; ++$i) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setDescription('description of product '.$i);
            $product->setPrice(mt_rand(10, 1000));
            $product->setPrice(mt_rand(10, 1000));
            $product->setPrice(mt_rand(10, 1000));
            $product->setPrice(mt_rand(10, 1000));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
