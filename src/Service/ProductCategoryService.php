<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;

class ProductCategoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addProductCategory(int $productId, int $categoryId)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $productCategory = $product->getCategory();

        for ($i = 0; $i < count($productCategory); $i++) {
            if ($categoryId == $productCategory[$i]->getId())
            {
                return false;
            }
        }

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if ($category == null) {
            return false;
        }

        $product->getCategory()->add($category);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }

    public function removeProductCategory(int $productId, int $categoryId)
    {
        $productCategory = $this->entityManager->getRepository(Product::class)->find($productId);
        for ($i = 0; $i < count($productCategory->getCategory()); $i++) {
            if ($productCategory->getCategory()[$i]->getId() == $categoryId) {
                $productCategory->removeCategory($productCategory->getCategory()[$i]);
            }
        }
        $this->entityManager->persist($productCategory);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }

//    public function showProductCategory(int $productId)
//    {
//        $product = $this->entityManager->getRepository(Product::class)->find($productId);
//        $productName = $product->getName();
//        $productCategory = $product->getCategory();
//
//        $category = [];
//        for ($i = 0; $i < count($productCategory); $i++) {
//            $category[$i]['id'] = $productCategory[$i]->getId();
//            $category[$i]['name'] = $productCategory[$i]->getName();
//        }
//        $category['productName'] = $productName;
//        return $category;
//    }
}