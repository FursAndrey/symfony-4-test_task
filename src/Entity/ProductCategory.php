<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCategory
{
    private $category_id;

    private $product_id;

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }
}