<?php

namespace App\Controller;

use App\Form\AddProductUserType;
use App\Form\AddUserProductType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\ProductUserService as PUS;

/**
 * Class ProductUserCommunication
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class ProductUserCommunication extends AbstractController
{
    /**
     * @Route("/delete_product_user/{userId}/{productId}", name="delete_product_user")
     */
    public function deleteProductUser($userId, $productId, VS $validatorService, PUS $productUserService)
    {
        $validUserId = $validatorService->isValid('int', $userId);
        if ($validUserId != []) {
            return $this->redirectToRoute('error');
        }

        $validProductId = $validatorService->isValid('int', $productId);
        if ($validProductId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $productUserService->removeProductToUser($productId, $userId);
        if ($result) {
            return $this->redirectToRoute('product_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/add_product_user/{id}", name="add_product_user")
     */
    public function addProductUser($id, Request $request, VS $validatorService, PUS $productUserService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $form = $this->createForm(AddProductUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $form->getData()->getUserId();

            $result = $productUserService->addProductToUser($id, $userId);
            if (!$result) {
                return $this->redirectToRoute('error');
            }
            return $this->redirectToRoute('product_page');
        }
        return $this->render('userProduct/addProductUser.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'sendBtn' => 'Добавить',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/add_user_product/{id}", name="add_user_product")
     */
    public function addUserProduct($id, Request $request, VS $validatorService, PUS $productUserService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $form = $this->createForm(AddUserProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productId = $form->getData()->getProductId();

            $result = $productUserService->addProductToUser($productId, $id);
            if (!$result) {
                return $this->redirectToRoute('error');
            }
            return $this->redirectToRoute('user_page');
        }
        return $this->render('userProduct/addUserProduct.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'sendBtn' => 'Добавить',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/delete_user_product/{userId}/{productId}", name="delete_user_product")
     */
    public function deleteUserProduct($userId, $productId, VS $validatorService, PUS $productUserService)
    {
        $validUserId = $validatorService->isValid('int', $userId);
        if ($validUserId != []) {
            return $this->redirectToRoute('error');
        }

        $validProductId = $validatorService->isValid('int', $productId);
        if ($validProductId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $productUserService->removeProductToUser($productId, $userId);
        if ($result) {
            return $this->redirectToRoute('user_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }
}