<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rating::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield NumberField::new('value');
        yield AssociationField::new('file', 'File');
        yield AssociationField::new('user', 'User');
    }
}
