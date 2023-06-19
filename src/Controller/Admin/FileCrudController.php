<?php

namespace App\Controller\Admin;

use App\Entity\CategoryEnum;
use App\Entity\File;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return File::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $enumsArr = CategoryEnum::cases();
        $values = array_column($enumsArr, 'value');

        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('type');
        yield AssociationField::new('user', 'Uploader');
        yield AssociationField::new('subject', 'Subject');
        yield ChoiceField::new('category')
            ->setChoices(array_combine($values, $values));
        yield TextField::new('extra');
        yield TextField::new('url');
        yield TextField::new('uniqueName');
        yield AssociationField::new('ratings', 'Ratings');
    }
}
