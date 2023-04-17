<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductOption;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductOption::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('taille'),
            TextField::new('forme'),
            AssociationField::new('product')
        ];
    }
    
}
