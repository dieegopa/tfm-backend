<?php

namespace App\Controller\Admin;

use App\Entity\Degree;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DegreeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Degree::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('slug');
        yield AssociationField::new('university', 'University');
        yield AssociationField::new('subject', 'Subjects');
        yield AssociationField::new('users', 'User');
        yield TextField::new('school');
        yield AssociationField::new('courses', 'Courses');
    }

}
