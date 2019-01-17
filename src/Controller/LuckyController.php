<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
  * Require ROLE_ADMIN for *every* controller method in this class.
  * @IsGranted("ROLE_USER")
  */
class LuckyController extends AbstractController
{
//    /**
//     * @Route("/addRole/{id}", name="add_role")
//     */
//    public function addUserRole($id)
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $user = $entityManager->getRepository(User::class)->find($id);
//
//        $roles = $user->getRoles();
//        $newRole = 'ROLE_ADMIN';
//
//        if (!array_search($newRole, $roles)) {
//            $roles[] = $newRole;
//            $user->setRoles($roles);
//            $entityManager->flush($user);
//        }
//
//        exit();
//    }
//    /**
//     * @Route("/delRole/{id}", name="del_role")
//     */
//    public function delUserRole($id)
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $user = $entityManager->getRepository(User::class)->find($id);
//
//        $roles = $user->getRoles();
//        $delRole = 'ROLE_ADMIN';
//        $a = array_search($delRole, $roles);
//        if ($a) {
//            unset($roles[$a]);
//            $user->setRoles($roles);
//            $entityManager->flush($user);
//        }
//
//        exit();
//    }

    /**
     * @Route("/number", name="number")
     */
    public function number()
    {
        $number = random_int(0, 100);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        return $this->render('lucky/base.html.twig', array(
            'number' => $number,
            'user' => $user->getUsername(),
        ));
    }
    /**
     * @Route("/adminNumber", name="admin_number")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminNumber()
    {
        $number = random_int(0, 100);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        return $this->render('lucky/role.html.twig', array(
            'number' => $number,
            'user' => $user->getUsername(),
        ));
    }
}