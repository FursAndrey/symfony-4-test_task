<?php

namespace App\Service;

use App\Entity\Phone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PhoneService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $phoneNumber, $id)
    {
        $phone = $this->entityManager->getRepository(Phone::class)->findOneBy(['phonenumber' => $phoneNumber]);
        if ($phone != null) {
            $error = 'This phone number is already registered.';
        } else {
            $user = $this->entityManager->getRepository(User::class)->find($id);
            $phone = new Phone();
            $phone->setPhonenumber($phoneNumber);
            $phone->setUsers($user);
            $this->entityManager->persist($phone);
            $this->entityManager->flush();
            $this->entityManager->clear();
            $error = '';
        }
        return $error;
    }

    public function delete(int $id)
    {
        $user = $this->entityManager->getRepository(Phone::class)->find($id);

        if ($user == null) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }
}