<?php
namespace App\Form;

use App\Entity\ProductCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;

use App\Entity\Category;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductCategoryType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository(Category::class);

        $categoryFromDB = $repository->findAll();
        $category = [];
        for ($i = 0; $i < count($categoryFromDB); $i++) {
            $category[$categoryFromDB[$i]->getName()] = $categoryFromDB[$i]->getId();
        }

        $builder
            ->add('categoryId', ChoiceType::class, array(
                'choices' => $category,
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductCategory::class,
        ));
    }
}