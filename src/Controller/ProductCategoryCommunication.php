<?php

namespace App\Controller;

use App\Form\AddProductCategoryType;
use App\Form\AddCategoryProductType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\ProductCategoryService as PCS;

/**
 * Class ProductCategoryCommunication
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class ProductCategoryCommunication extends AbstractController
{
    /**
     * @Route("/add_product_category/{id}", name="add_product_category")
     */
    public function addProductCategory($id, Request $request, VS $validatorService, PCS $productCategoryService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $form = $this->createForm(AddProductCategoryType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addProductCategory = $form->getData();

            $result = $productCategoryService->addProductCategory($id, $addProductCategory->getCategoryId());
            if (!$result) {
                return $this->redirectToRoute('error');
            }
            return $this->redirectToRoute('product_page');
        }
        return $this->render('productCategory/addProductCategory.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'sendBtn' => 'Добавить',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/add_category_product/{id}", name="add_category_product")
     */
    public function addCategoryProduct($id, Request $request, VS $validatorService, PCS $productCategoryService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $form = $this->createForm(AddCategoryProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addProductCategory = $form->getData();

            $result = $productCategoryService->addProductCategory($addProductCategory->getProductId(), $id);
            if (!$result) {
                return $this->redirectToRoute('error');
            }
            return $this->redirectToRoute('category_page');
        }
        return $this->render('productCategory/addCategoryProduct.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'sendBtn' => 'Добавить',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/delete_product_category/{categoryId}/{productId}", name="delete_product_category")
     */
    public function deleteProductCategory($categoryId, $productId, VS $validatorService, PCS $productCategoryService)
    {
        $validCategoryId = $validatorService->isValid('int', $categoryId);
        if ($validCategoryId != []) {
            return $this->redirectToRoute('error');
        }

        $validProductId = $validatorService->isValid('int', $productId);
        if ($validProductId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $productCategoryService->removeProductCategory($productId, $categoryId);
        if ($result) {
            return $this->redirectToRoute('product_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/delete_category_product/{categoryId}/{productId}", name="delete_category_product")
     */
    public function deleteCategoryProduct($categoryId, $productId, VS $validatorService, PCS $productCategoryService)
    {
        $validCategoryId = $validatorService->isValid('int', $categoryId);
        if ($validCategoryId != []) {
            return $this->redirectToRoute('error');
        }

        $validProductId = $validatorService->isValid('int', $productId);
        if ($validProductId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $productCategoryService->removeProductCategory($productId, $categoryId);
        if ($result) {
            return $this->redirectToRoute('category_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }
}