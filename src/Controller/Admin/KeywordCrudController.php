<?php

namespace App\Controller\Admin;

use App\Entity\Keyword;
use App\Entity\Section;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

#[IsGranted('ROLE_ADMIN')]
class KeywordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Keyword::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Keyword Name'),
            AssociationField::new('section', 'Section')
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'nom')
                ->setFormTypeOption('placeholder', 'Choose a section')
        ];
    }
}