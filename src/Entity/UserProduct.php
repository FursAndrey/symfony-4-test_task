<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserProduct
{
    private $user_id;
    /**
     * @var
     */
    private $product_id;


    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($productId)
    {
        $this->product_id = $productId;
    }
}