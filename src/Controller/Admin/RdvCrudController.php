<?php

namespace App\Controller\Admin;

use App\Entity\Rdv;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;



class RdvCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rdv::class;
    }
    public function configureCrud(Crud $crud):Crud{
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextEditorField::new('description'),
            DateTimeField::new('dateRdv'),
            AssociationField::new('prestations'),
            AssociationField::new('client'),
        ];
    }
    
}
