<?php

namespace App\Controller\Admin;

use App\Entity\BuzzPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BuzzPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BuzzPost::class;
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
