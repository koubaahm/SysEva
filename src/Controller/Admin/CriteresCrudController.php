<?php

namespace App\Controller\Admin;

use App\Entity\Criteres;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

#[IsGranted('ROLE_ADMIN')]
class CriteresCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Criteres::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('intitule'),
            IntegerField::new('coefficient'),
        ];
    }

}
