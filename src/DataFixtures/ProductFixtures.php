<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $commerceProvider = new Commerce($faker);
        for ($i = 0; $i < 120; $i++) {
            $productName = $commerceProvider->productName();
            $imageUrl = "https://craftypixels.com/placeholder-image/700x700/fff/000&text=" . urlencode($productName);
            $category = $commerceProvider->category();
            $product = new Product();
            $product->setCode(sprintf('PROD-%03d', $i + 1))
                ->setName($productName)
                ->setDescription($faker->paragraph)
                ->setImage($imageUrl)
                ->setCategory($category)
                ->setPrice($faker->randomFloat(2, 10, 1000))
                ->setQuantity($faker->numberBetween(0, 100))
                ->setInternalReference(sprintf('REF-%05d', $faker->unique()->randomNumber(5)))
                ->setRating($faker->randomFloat(1, 0, 5));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
