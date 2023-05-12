<?php

namespace App\Controller\Admin;

use App\Entity\University;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UniversityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return University::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('image_url');
        yield TextField::new('slug');
        yield AssociationField::new('degrees', 'Degrees');
        yield AssociationField::new('users', 'Users');
    }

}
