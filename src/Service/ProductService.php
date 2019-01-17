<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function show()
    {
        $repository = $this->entityManager->getRepository(Product::class);
        $productFromDB = $repository->findAll();

        $products = [];
        for ($i = 0; $i < count($productFromDB); $i++) {
            $products[$i]['id'] = $productFromDB[$i]->getId();
            $products[$i]['name'] = $productFromDB[$i]->getName();
            $products[$i]['price'] = ($productFromDB[$i]->getPrice());
            if ($productFromDB[$i]->getUsers()[0] != null) {
                for ($j = 0; $j < count($productFromDB[$i]->getUsers()); $j++) {
                    $products[$i]['user'][$j]['id'] = $productFromDB[$i]->getUsers()[$j]->getId();
                    $products[$i]['user'][$j]['nickname'] = $productFromDB[$i]->getUsers()[$j]->getNickname();
                }
            } else {
                $products[$i]['user'][0]['id'] = '0';
                $products[$i]['user'][0]['nickname'] = '-';
            }
            if ($productFromDB[$i]->getCategory()[0] != null) {
                for ($j = 0; $j < count($productFromDB[$i]->getCategory()); $j++) {
                    $products[$i]['category'][$j]['id'] = $productFromDB[$i]->getCategory()[$j]->getId();
                    $products[$i]['category'][$j]['name'] = $productFromDB[$i]->getCategory()[$j]->getName();
                }
            } else {
                $products[$i]['category'][0]['id'] = '0';
                $products[$i]['category'][0]['name'] = '-';
            }
        }

//        echo "<pre>";
////        print_r($products);
//        var_dump($productFromDB[0] instanceof Product);
//        var_dump($productFromDB[0]->getUsers()[0] instanceof User);
//        var_dump($productFromDB[0]->getCategory()[0] instanceof Category);
//        exit();

        return $products;
    }

    public function create(string $name, float $price)
    {
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }

    public function delete(int $id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if ($product == null) {
            $result = 'Product does not exist.';
            return $result;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }

    public function update(float $price, int $id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }
}