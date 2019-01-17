<?php
namespace App\Form;

use App\Entity\ProductCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;

use App\Entity\Product;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCategoryProductType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository(Product::class);

        $productFromDB = $repository->findAll();
        $products = [];
        for ($i = 0; $i < count($productFromDB); $i++) {
            $products[$productFromDB[$i]->getName()] = $productFromDB[$i]->getId();
        }

        $builder
            ->add('productId', ChoiceType::class, array(
                'choices' => $products,
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