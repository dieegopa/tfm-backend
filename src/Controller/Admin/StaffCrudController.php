<?php

namespace App\Controller\Admin;

use App\Entity\Staff;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StaffCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Staff::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('email');
        yield ArrayField::new('roles');
        yield TextField::new('password');
    }

}
