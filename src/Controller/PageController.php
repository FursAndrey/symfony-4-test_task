<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\PageService as PS;
use App\Service\ValidatorService as VS;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", name="first")
     */
    public function firstPage()
    {
        return $this->render('page/firstPage.html.twig', array(
            'login' => 'Авторизация',
            'register' => 'Регистрация'
        ));
    }

    /**
     * @Route("/error", name="error")
     */
    public function errorPage()
    {
        return $this->render('page/errorPage.html.twig', array(
            'error' => 'Error',
            'descriptionError' => 'Bad request',
            'Head' => 'Return to head page.',
        ));
    }
}