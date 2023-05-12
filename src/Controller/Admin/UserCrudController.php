<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('sub');
        yield TextField::new('email');
        yield ArrayField::new('roles');
        yield AssociationField::new('universities', 'Universities');
        yield AssociationField::new('subjects', 'Subjects');
        yield AssociationField::new('degrees', 'Degrees');
        yield AssociationField::new('files', 'Files');
        yield AssociationField::new('ratings', 'Ratings');
    }

}
