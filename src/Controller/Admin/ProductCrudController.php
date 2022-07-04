<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    { //permet de configurer les input et leur format de easyAdmin 
        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'), //on ne pourra pas changer directement le slug car il est relié au name
            ImageField::new('illustartion')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/') //on envoi l'image dans le dossier uploads
                ->setUploadedFileNamePattern('[randomhash].[extension]') //on renome le fichier avec randomhash 
                ->setRequired('false'),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            BooleanField::new('isBest'),
            MoneyField::new('prix')->setCurrency('EUR'), //permet de définir la monaie 
            AssociationField::new('category')
        ];
    }
}
