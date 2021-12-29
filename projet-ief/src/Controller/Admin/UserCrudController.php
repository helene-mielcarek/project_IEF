<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private $userPasswordHasher;

    public function __construct( UserPasswordHasherInterface $userPasswordHasher)
    {   
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            // ->add(Crud::PAGE_DETAIL, Action::DETAIL)
            // ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
        ;
    }
    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters
    //         ->add(EntityFilter::new('roles'))
    //    ;
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Nom')->setCssClass('js-row-action')->hideOnIndex(),
            TextField::new('name', 'Nom')->setCssClass('js-row-action')->addCssClass('js-row-click')->onlyOnIndex(),

            EmailField::new('email', 'Adresse électronique'),

            TextField::new('password', 'Mot de passe')->onlyOnForms()
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer votre mot de passe'],
                'first_name' => 'first_password',
                'second_name' => 'second_password',
                'invalid_message' => 'Les deux valeurs doivent être identiques'
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms(),

            TextField::new('description', 'descritption')->hideOnIndex(),

            ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Membre' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN'
            ])
            ->allowMultipleChoices()
            ->hideOnIndex(),
            ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Utilisateur' => 'ROLE_USER',
                'Membre' => 'ROLE_MEMBER',
                'Administrateur' => 'ROLE_ADMIN'
            ])
            ->setCssClass('js-row-click')->onlyOnIndex(),

            IntegerField::new('nbChildren', 'Nombre d\'enfant')
            ->setCssClass('js-row-click')->onlyOnIndex(),
            IntegerField::new('nbChildren', 'Nombre d\'enfant')
            ->hideOnIndex(),

            DateTimeField::new('createdAt', 'Date de création')->onlyOnIndex()->setCssClass('js-row-click')->setFormat('dd-MM-Y'),
            DateTimeField::new('updatedAt', 'Dernière modification')->onlyOnIndex()->setCssClass('js-row-click')->setFormat('dd-MM-Y'),
            ArrayField::new('Events', 'Événements créés')->onlyOnDetail()
            ->setFormType(EntityType::class)
            ->setFormTypeOptions([
                'class' => Event::class
            ])
            ->setCssClass('js-event-action')
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        $entityInstance->setCreatedAt(new \DateTime('now'));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        $entityInstance->setUpdatedAt(new \DateTime('now'));
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function encodePassword(User $user)
    {
        // dd($user);
        if ($user->getPassword() !== null) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
            // $user->setSalt(base_convert(bin2hex(random_bytes(20)), 16, 36));
            // This is where you use UserPasswordEncoderInterface
            // $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        }
    }
    public function configureAssets(Assets $assets): Assets {
        return $assets
        ->addWebpackEncoreEntry('admin-app')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Utilisateurs');
    }
}
