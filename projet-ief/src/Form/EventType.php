<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'titre',
                'required' => true,
                'attr'=> [
                    'placeholder' => 'Entre ton titre'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Il nous faut un titre']),
                    new Length([
                        'min' => 5,
                        'max' => 100])
                ],

            ])
            ->add('date', DateTimeType::class, [
                'label' => 'date de l\'événement',
                'required' => true,
                'data' => new \DateTime("now"),
                'constraints' => [
                    // impossible de mettre new ConstraintsDateTime(), car date est demander en 2 input ('day' 'hour'),
                    new NotBlank(),
                    // Si on veut limiter la création que pour des event futurs
                    // new GreaterThan(['value' => new \DateTime("now")])
                ]
            ])
            ->add('lieu', TextType::class, [
                'label' => 'lieu de l\'évenement',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Où se déroulera-t-il?'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Décris-nous en quelques lignes...'
                ]
            ])
            ->add('limite', ChoiceType::class, [
                // si nombre de famille (class User)
                'label' => 'Nombre limité de familles participantes',
                // si nb d'enfants (class Participant)
                // 'label' => 'Nombre limité de participants',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices' => [
                    'Aucune limitation' => 0,
                    $this->implementLimit(),
                ],
                'group_by' => 'null',
            ])
            ->add('img', FileType::class, [
                'label' => 'Image d\'illustration',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',

                        ],
                        'mimeTypesMessage' => "Ce document est invalide",
                        'maxSize' => '2M',
                        'maxSizeMessage' => "Le fichier doit faire 2 Méga octet maximum."
                    ], 
                )],
                'help' => 'Taille max: 2 Mo'
            ])
            ->add('complet', ChoiceType::class, [
                'label' => 'Complet?',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
            ])
            ->add('famParticipants'    //si on utilise un nombre de participant(enfant) mettre perticipants, sinon famParticipants
            , EntityType::class, [
                'label' => false,
                 //si on utilise un nombre de participant(enfant)
                // 'class' => Participant::class,
                //si on utilise un nombre de familles participantes(USER)
                'class' => User::class,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                //si on utilise un nombre de participant(enfant)
                // 'query_builder' => function(ParticipantRepository $participantRepository){
                //     $participantRepository->findForFormEvent();
                // }
                //si on utilise un nombre de familles participantes(USER)
                'query_builder' => function(UserRepository $userRepository){
                    return $userRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
            ->add('category', EntityType::class, [
                'label' => 'Choisis une ou des catégories',
                'class' => Category::class,
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function(CategoryRepository $categoryRepository){
                    return $categoryRepository->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'max' => 1,
                        'minMessage' => 'Choisi au moins un catégorie.',
                        'maxMessage' => 'Tu peux choisir qu\'une catégorie.'
                    ])
                    ],
                'help' => "Choisi une catégorie."
            ])
            ->add('nbParticipants', IntegerType::class, [
                'label' => 'Combien serez-vous?',
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

    /**
     * implémentation du select Limite
     *
     * @return void
     */
    public function implementLimit() {
        $data1 = [];
        $data2 = [];
        for ($i =0; $i <= 25 ; $i++){
            if($i > 2){
                $data1[] = $i;
            }
        }
        for ($i =0; $i <= 25 ; $i++){
            if ($i == 0 || $i > 2){
                $data2[] = $i;
            }
        }
        return array_combine($data1, $data1);
    }
}
