<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Organisation;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'organisation',
                'attr' => [
                    'placeholder' => 'Nom de l\'organisation',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'organisation',
                'attr' => [
                    'placeholder' => 'Description de l\'organisation',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie de l\'organisation',
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => fn (CategoryRepository $categoryRepository) => $categoryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC'),
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
