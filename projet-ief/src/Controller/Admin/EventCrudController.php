<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\User;
use App\Field\VichImageField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('title', 'Titre')->setCssClass('js-row-action')->hideOnIndex(),
            TextField::new('title', 'Titre')->setCssClass('js-row-action')->addCssClass('js-row-click')->onlyOnIndex(),

            TextField::new('author', 'Auteur'),

            DateTimeField::new('date', 'Date de l\'événement')->setCssClass('js-row-click')->onlyOnIndex()->setFormat('dd-MM-Y'),
            DateTimeField::new('date', 'Date de l\'événement')->hideOnIndex()->setFormat('dd-MM-Y'),

            TextField::new('lieu', 'Lieu')->setCssClass('js-row-click')->onlyOnIndex(),
            TextField::new('lieu', 'Lieu')->hideOnIndex(),

            TextEditorField::new('description', 'Descritpion'),

            IntegerField::new('limite', 'Limitation')
            ->setCssClass('js-row-click')->onlyOnIndex(),
            IntegerField::new('limite', 'Limitation')
            ->hideOnIndex(),

            BooleanField::new('complet', 'Complet'),

            // Si utilisation du nb d'enfants
            // ArrayField::new('participants', 'Participants') 
            // ->onlyOnDetail(),
            // AssociationField::new('participants', 'Participants'),
            //sinon
            ArrayField::new('famParticipants', 'Participants') 
            ->onlyOnDetail()
            ,


            VichImageField::new('img')->onlyOnDetail(),
            VichImageField::new('img_file')->onlyOnForms(),


            DateTimeField::new('createdAt', 'Date de création')->hideOnForm()->setCssClass('js-row-click')->setFormat('dd-MM-Y'),
            DateTimeField::new('updatedAt', 'Dernière modification')->onlyOnDetail()->setCssClass('js-row-click'),


        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setCreatedAt(new \DateTime('now'));
        dd($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime('now'));
        parent::updateEntity($entityManager, $entityInstance);

    }

    public function configureAssets(Assets $assets): Assets {
        return $assets
        ->addWebpackEncoreEntry('admin-app')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Événements')
            ->overrideTemplates([
                'crud/index' => 'bundles//EasyAdminBundle/crud/event/index.html.twig',
                'crud/detail' => 'bundles//EasyAdminBundle/crud/event/detail.html.twig',
            ])
            ;
    }
}
