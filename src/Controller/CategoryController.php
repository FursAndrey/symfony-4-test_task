<?php

namespace App\Controller;

use App\Entity\Category;

use App\Form\CreateCategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\CategoryService;
use App\Service\ValidatorService as VS;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/create_category", name="create_category")
     * @IsGranted("ROLE_PRODUCT_MANAGER")
     */
    public function createCategory(Request $request, CategoryService $categoryService)
    {
        $createCategory = new Category();
        $form = $this->createForm(CreateCategoryType::class, $createCategory);
        $form->handleRequest($request);

        $result = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $nameCategory = $form->getData()->getName();

            $result = $categoryService->create($nameCategory);
            if ($result == '') {
                return $this->redirectToRoute('category_page');
            }
        }
        return $this->render('category/create.html.twig', array(
            'form' => $form->createView(),
            'error' => $result,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }

    /**
     * @Route("/category_page", name="category_page")
     * @IsGranted("ROLE_USER")
     */
    public function showCategory(CategoryService $categoryService)
    {
        $result = $categoryService->show();
        return $this->render('category/show.html.twig', array(
            'Create' => 'Создать категорию',
            'ViewUser' => 'Показать пользователей',
            'ViewProduct' => 'Показать продукты',
            'AddProduct' => 'Добавить продукт',
            'Exit' => 'Выйти',
            'Delete' => 'Удалить',
            'category' => $result,
            'tableHead' => [
                'Название',
                'Продукты',
                'Профиль категории'
            ]
        ));
    }

    /**
     * @Route("/delete_category/{id}", name="delete_category")
     * @IsGranted("ROLE_PRODUCT_MANAGER")
     */
    public function deleteCategory(int $id, VS $validatorService, CategoryService $categoryService)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $result = $categoryService->delete($id);
        if ($result == '') {
            return $this->redirectToRoute('category_page');
        } else {
            return $this->redirectToRoute('error');
        }
    }
}