<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Field\VichImageField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom')->setCssClass('js-row-action')->hideOnIndex(),
            TextField::new('name', 'Nom')->setCssClass('js-row-action')->addCssClass('js-row-click')->onlyOnIndex(),
            TextEditorField::new('description', 'Descritpion'),
            ColorField::new('color', 'Couleur'),
            VichImageField::new('img_defaut')->hideOnForm(),
            VichImageField::new('img_defaut_file')->onlyOnForms(),

            DateTimeField::new('createdAt', 'Date de création')->hideOnForm()->setCssClass('js-row-click')->setFormat('dd-MM-Y'),
            DateTimeField::new('updatedAt', 'Dernière modification')->hideOnForm()->setCssClass('js-row-click')->setFormat('dd-MM-Y'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setCreatedAt(new \DateTime('now'));
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
            ->setPageTitle('index', 'Catégories');
    }
}
