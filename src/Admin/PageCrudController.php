<?php

namespace VladX\PagesBundle\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use VladX\PagesBundle\Entity\PageInterface;

/**
 * @extends AbstractCrudController<PageInterface>
 */
abstract class PageCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        return array_merge([
            IdField::new('id')->hideOnForm(),
            FormField::addTab('General'),
            TextField::new('title'),
            SlugField::new('slug')->setTargetFieldName('title'),
            AssociationField::new('parent'),
        ], MetaFields::getSeoTab());
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->computeFullSlug();
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->computeFullSlug();
        parent::updateEntity($entityManager, $entityInstance);
        $entityInstance->cascadeFullSlug();
        $entityManager->flush();
    }
}
