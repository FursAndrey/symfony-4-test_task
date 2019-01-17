<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Form\PhoneType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\PhoneService as PS;

/**
 * Class PhoneController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class PhoneController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create_phone/{id}", name="create_phone")
     */
    public function createPhone($id, Request $request, PS $phoneService, VS $validatorService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $createphone = new Phone();
        $form = $this->createForm(PhoneType::class, $createphone);
        $form->handleRequest($request);

        $error = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $form->getData()->getPhonenumber();
            $error = $phoneService->create($phone, $id);

            if ($error == '') {
                return $this->redirectToRoute('user_page');
            }
        }
        return $this->render('phone/create.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/delete_phone/{id}", name="delete_phone")
     */
    public function deletePhone($id, VS $validatorService, PS $phoneService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $phoneService->delete($id);
        if (!$result) {
            return $this->redirectToRoute('error');
        }
        return $this->redirectToRoute('user_page');
    }
}