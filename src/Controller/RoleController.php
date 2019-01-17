<?php

namespace App\Controller;

use App\Form\UpdateRoleType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\ValidatorService as VS;
use App\Service\RoleService as RS;
/**
 * Class RoleController
 * @package App\Controller
 */
class RoleController extends AbstractController
{
    /**
     * @Route("/updateRole/{id}", name="update_role")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateRole($id, VS $validatorService, RS $roleService, Request $request)
    {
        $validId = $validatorService->isValid('int', $id);
        if ($validId != []) {
            return $this->redirectToRoute('error');
        }

        $form = $this->createForm(UpdateRoleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->getData()->getRoles();

            $error = $roleService->update($role, $id);

            return $this->redirectToRoute('user_page');
        }
        return $this->render('user/role.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'sendBtn' => 'Сохранть',
            'returnBtn' => 'Назад'
        ));
    }
}