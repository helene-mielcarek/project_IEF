<?php
namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('date', ChoiceType::class, [
                'label' => 'date:',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'à venir' => false,
                    'passés' => true,
                    'tous' => null,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('limite', ChoiceType::class, [
                'label' => 'limitation:',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'incomplets' => false,
                    'complets' => true,
                    'tous' => null,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}