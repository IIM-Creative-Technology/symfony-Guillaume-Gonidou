<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $manager->persist($category);
            $this->addReference('category_' . $i, $category);
        }

        $manager->flush();

        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        foreach ($categories as $category) {
            for ($i = 0; $i < 20; $i++) {
                $product = new Product();
                $product->setName('Product ' . $i);
                $product->setPrix(rand(1, 100));
                $product->setStock(rand(1, 300));
                $product->setDescription('Description ' . $i);
                $product->setCategory($category);
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
