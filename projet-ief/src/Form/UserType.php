<?php

namespace App\Form;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse E-Mail',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mon@adresse.mail'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer uneadresse mail.']),
                    new Email(['message' => 'Veuillez entrer une adresse mail valide.'])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer votre mot de passe'],
                'first_name' => 'first_password',
                'second_name' => 'second_password',
                'invalid_message' => 'Les deux valeurs doivent être identiques',
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Choisissez un nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre nom, pseudo'
                ],
                'help' => 'par ex: "Hélène et les garçons"',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Décrivez-vous en quelques mots.',
                'required' => true,
                'attr' => [
                    'placeholder' => 'En ief depuis peu, sur la commune de Lorient......'
                ]
            ])
            ->add('nbChildren', IntegerType::class, [
                'label' => 'Combien y aurait-il d\'enfants en général aux événements?',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
