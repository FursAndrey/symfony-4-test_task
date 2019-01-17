<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $name)
    {
        $category = new Category();
        $category->setName($name);

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }

    public function delete(int $id)
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        if ($category == null) {
            $result = 'Product does not exist.';
            return $result;
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }

    public function show()
    {
        $repository = $this->entityManager->getRepository(Category::class);
        $categoryFromDB = $repository->findAll();

        $category = [];
        for ($i = 0; $i < count($categoryFromDB); $i++) {
            $category[$i]['id'] = $categoryFromDB[$i]->getId();
            $category[$i]['name'] = $categoryFromDB[$i]->getName();
            if ($categoryFromDB[$i]->getProducts()[0] != null) {
                for ($j = 0; $j < count($categoryFromDB[$i]->getProducts()); $j++) {
                    $category[$i]['product'][$j]['id'] = $categoryFromDB[$i]->getProducts()[$j]->getId();
                    $category[$i]['product'][$j]['name'] = $categoryFromDB[$i]->getProducts()[$j]->getName();
                }
            } else {
                $category[$i]['product'][0]['name'] = '-';
                $category[$i]['product'][0]['id'] = '0';
            }
        }
        return $category;
    }
}