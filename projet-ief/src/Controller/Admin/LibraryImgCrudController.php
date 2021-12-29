<?php

namespace App\Controller\Admin;

use App\Entity\LibraryImg;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LibraryImgCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LibraryImg::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
