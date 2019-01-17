<?php

namespace App\Controller;

use App\Entity\Product;

use App\Form\UpdateProductType;
use App\Form\CreateProductType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\ProductService;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product_page", name="product_page")
     * @IsGranted("ROLE_USER")
     */
    public function productPage(ProductService $productService)
    {
        $result = $productService->show();

        return $this->render('product/show.html.twig', array(
            'Create' => 'Создать продукт',
            'ViewUser' => 'Показать пользователей',
            'ViewCategory' => 'Показать категории',
            'AddUser' => 'Добавить пользователя',
            'AddCategory' => 'Добавить категорию',
            'Exit' => 'Выйти',
            'Show' => 'Показать',
            'Update' => 'Редактировать',
            'Delete' => 'Удалить',
            'products' => $result,
            'tableHead' => [
                'Название',
                'Цена',
                'Пользователи продукта',
                'Категории продукта',
                'Профиль продукта'
            ]
        ));
    }

    /**
     * @Route("/create_product", name="create_product")
     * @IsGranted("ROLE_PRODUCT_MANAGER")
     */
    public function createProduct(Request $request, ProductService $productService)
    {
        $createProductData = new Product();
        $form = $this->createForm(CreateProductType::class, $createProductData);
        $form->handleRequest($request);

        $result = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $nameProduct = $form->getData()->getName();
            $priceProduct = $form->getData()->getPrice();

            $result = $productService->create($nameProduct, $priceProduct);
            if ($result == '') {
                return $this->redirectToRoute('product_page');
            }
        }

        return $this->render('product/create.html.twig', array(
            'form' => $form->createView(),
            'error' => $result,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/delete_product/{id}", name="delete_product")
     * @IsGranted("ROLE_PRODUCT_MANAGER")
     */
    public function deleteProduct(int $id, VS $validatorService, ProductService $productService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $productService->delete($id);

        if ($result == '') {
            return $this->redirectToRoute('product_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/update_product/{id}", name="update_product")
     * @IsGranted("ROLE_PRODUCT_MANAGER")
     */
    public function updateProduct(int $id, Request $request, VS $validatorService, ProductService $productService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        if ($product == null) {
            return $this->redirectToRoute('error');
        }

        $result = false;
        $form = $this->createForm(UpdateProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $priceProduct = $form->getData()->getPrice();

            $result = $productService->update($priceProduct, $id);
            if ($result == '') {
                return $this->redirectToRoute('product_page');
            }
        }

        return $this->render('product/update.html.twig', array(
            'form' => $form->createView(),
            'error' => $result,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }
}