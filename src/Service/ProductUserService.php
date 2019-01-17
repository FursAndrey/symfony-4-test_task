<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

class ProductUserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addProductToUser(int $productId, int $userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $userProducts = $user->getProducts();
        for ($i = 0; $i < count($userProducts); $i++) {
            if ($productId == $userProducts[$i]->getId())
            {
                return false;
            }
        }

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if ($product == null) {
            return false;
        }
        $user->getProducts()->add($product);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }

    public function removeProductToUser(int $productId, int $userId)
    {
        $userProduct = $this->entityManager->getRepository(User::class)->find($userId);
        for ($i = 0; $i < count($userProduct->getProducts()); $i++) {
            if ($userProduct->getProducts()[$i]->getId() == $productId) {
                $userProduct->removeProduct($userProduct->getProducts()[$i]);
            }
        }
        $this->entityManager->persist($userProduct);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }
}