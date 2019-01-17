<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use App\Form\UpdateUserType;
use App\Form\UpdateRoleType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\UserService as US;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, US $userService)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $error = '';
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $login = $user->getLogin();
            $pass = $user->getPlainPassword();
            $nickname = $user->getNickname();
            $email = $user->getEmail();
            $date = $user->getBirthdate();

            $error = $userService->create($login, $pass, $nickname, $email, $date);

            if ($error == '') {
                return $this->redirectToRoute('user_page');
            }
        }
        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
            'sendBtn' => 'Зарегистрировать',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/user_page", name="user_page")
     * @IsGranted("ROLE_USER")
     */
    public function userPage(US $userService)
    {
        $result = $userService->show();
        if (!$result) {
            return $this->redirectToRoute('error');
        }

        return $this->render('user/show.html.twig', array(
            'Create' => 'Создать пользователя',
            'View' => 'Показать продукты',
            'Exit' => 'Выйти',
            'AddPhone' => 'Добавить телефон',
            'AddProduct' => 'Добавить продукт',
            'Update' => 'Редактировать',
            'Delete' => 'Удалить',
            'users' => $result,
            'tableHead' => [
                'Имя пользователя',
                'Адрес электронной почты',
                'Номер телефона',
                'Дата рождения',
                'Продукты пользователя',
                'Роль',
                'Профиль пользователя'
            ]
        ));
    }

    /**
     * @Route("/user_by_id/{id}", name="user_by_id")
     * @IsGranted("ROLE_USER")
     */
    public function userByIdPage($id, VS $validatorService, US $userService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $userService->showById($id);
        if (!$result) {
            return $this->redirectToRoute('error');
        }

        return $this->render('user/showById.html.twig', array(
            'Create' => 'Создать пользователя',
            'View' => 'Показать продукты',
            'ViewUser' => 'Показать пользователей',
            'Exit' => 'Выйти',
            'AddPhone' => 'Добавить телефон',
            'AddProduct' => 'Добавить продукт',
            'Update' => 'Редактировать',
            'Delete' => 'Удалить',
            'user' => $result,
            'tableHead' => [
                'Имя пользователя',
                'Адрес электронной почты',
                'Номер телефона',
                'Дата рождения',
                'Продукты пользователя',
                'Роль',
                'Профиль пользователя'
            ]
        ));
    }

    /**
     * @Route("/delete_user/{id}", name="delete_user")
     * @IsGranted("ROLE_USER")
     */
    public function deleteUser($id, VS $validatorService, US $userService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }
        $userId = $this->getUser()->getId();
        if($userId == $id){
            return $this->redirectToRoute('error');
        }

        $result = $userService->delete($id);
        if (!$result) {
            return $this->redirectToRoute('login');
        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->redirectToRoute('user_page');
    }

    /**
     * @Route("/update_user/{id}", name="update_user")
     * @IsGranted("ROLE_USER")
     */
    public function updateuser($id, Request $request, VS $validatorService, US $userService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $user->setPlainPassword('pass');

        if ($user == null) {
            return $this->redirectToRoute('error');
        }
        $error = '';

        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userNickname = $form->getData()->getNickname();
            $userEmail = $form->getData()->getEmail();
            $userBirthdate = $form->getData()->getBirthdate();

            $result = $userService->update($userNickname, $userEmail, $userBirthdate, $id);

            if ($result == '') {
                return $this->redirectToRoute('user_page');
            }
        }
        return $this->render('user/update.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }
}