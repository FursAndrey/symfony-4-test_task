<?php

namespace App\Service;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update($role, $id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $newRole[] = $role;
        $user->setRoles($newRole);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $error = '';

        return $error;
    }
}