<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 06.01.2019
 * Time: 18:53
 */

namespace App\Entity;


class Role
{
    private $roles;

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}