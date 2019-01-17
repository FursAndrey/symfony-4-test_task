<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $entityManager;
    private $passwordEncoder;

    //пересмотреть все запросы к базе данных, вынести некоторые части в отдельный файл

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function show()
    {
        $repository = $this->entityManager->getRepository(User::class);
        $usersFromDB = $repository->findAll();

        for ($i = 0; $i < count($usersFromDB); $i++) {
            $users[] =  $this->getUserData($usersFromDB[$i]);
        }
        return $users;
    }

    public function showById($id)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $userFromDB = $repository->find($id);

        $user = $this->getUserData($userFromDB);

        return $user;
    }

    private function getUserData($userFromDB)
    {
        $user['id'] = $userFromDB->getId();
        $user['nickname'] = $userFromDB->getNickname();
        $user['email'] = $userFromDB->getEmail();
        $user['birthdate'] = $userFromDB->getBirthdate();
        if ($userFromDB->getPhone()[0] != null) {
            for ($j = 0; $j < count($userFromDB->getPhone()); $j++) {
                $user['phone'][$j]['id'] = $userFromDB->getPhone()[$j]->getId();
                $user['phone'][$j]['phone'] = $userFromDB->getPhone()[$j]->getPhonenumber();
            }
        } else {
            $user['phone'][0]['phone'] = '-';
            $user['phone'][0]['id'] = '0';
        }
        if ($userFromDB->getProducts()[0] != null) {
            for ($j = 0; $j < count($userFromDB->getProducts()); $j++) {
                $user['product'][$j]['id'] = $userFromDB->getProducts()[$j]->getId();
                $user['product'][$j]['name'] = $userFromDB->getProducts()[$j]->getName();
            }
        } else {
            $user['product'][0]['name'] = '-';
            $user['product'][0]['id'] = '0';
        }
        $role = $userFromDB->getRoles();
        $role = implode(',',$role);
        $user['role'] = str_replace(["ROLE_", '_'], " ", $role);
        return $user;
    }

    public function create(string $login, string $pass, string $nickname, string $email, \DateTimeInterface $birthdate)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => $login]);

        if ($user != null) {
            $error = 'User with this login already exists.';
        } else {
            $user = new User();
            $user->setLogin($login);
            $password = $this->passwordEncoder->encodePassword($user, $pass);
            $user->setPassword($password);
            $user->setNickname($nickname);
            $user->setEmail($email);
            $user->setBirthdate($birthdate);
            $user->setRoles(['ROLE_USER']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->clear();
            $error = '';
        }
        return $error;
    }

    public function delete(int $userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if ($user == null) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return true;
    }

    public function update(string $nickname, string $email, \DateTime $birthdate, int $userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $user->setNickname($nickname);
        $user->setEmail($email);
        $user->setBirthdate($birthdate);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->clear();
        $result = '';
        return $result;
    }
}