<?php
namespace App\Form;

use App\Entity\UserProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductUserType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository(User::class);

        $userFromDB = $repository->findAll();
        $users = [];
        for ($i = 0; $i < count($userFromDB); $i++) {
            $users[$userFromDB[$i]->getNickname()] = $userFromDB[$i]->getId();
        }

        $builder
            ->add('userId', ChoiceType::class, array(
                'choices' => $users,
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserProduct::class,
        ));
    }
}
