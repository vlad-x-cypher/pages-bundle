<?php

namespace VladX\PagesBundle\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use VladX\PagesBundle\Entity\PageInterface;
use VladX\PagesBundle\Form\SitemapType;
use VladX\PagesBundle\Form\TemplateType;
use VladX\PagesBundle\Utility\PagesTemplates;

/**
 * @extends AbstractCrudController<PageInterface>
 */
abstract class PageCrudController extends AbstractCrudController
{
    /**
     * @var FieldInterface[]
     */
    private array $generalFields = [];

    /**
     * @param PagesTemplates<array<string,array{path:string,form:string}>> $templatesHelper
     */
    public function __construct(
        private PagesTemplates $templatesHelper
    ) {
        $this->generalFields = [];
    }

    public function addGeneralFields(FieldInterface ...$fields): void
    {
        array_push($this->generalFields, ...$fields);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addTab('General');
        yield TextField::new('title');
        yield SlugField::new('slug')->setTargetFieldName('title');
        yield AssociationField::new('parent');
        if (count($this->generalFields) > 0) {
            yield from $this->generalFields;
        }
        if ($templateChoices = $this->templatesHelper->templatesAsChoices()) {
            yield FormField::addTab('Template');
            // yield ChoiceField::new('template')->setChoices($templateChoices);
            yield Field::new('virtualTemplate')
                ->setFormType(TemplateType::class)
                ->setLabel('')
                ->onlyOnForms()
            ;
        }
        yield from MetaFields::getSeoTab();
        yield FormField::addTab('Sitemap');
        yield SitemapField::new('sitemapConfig')
            ->onlyOnForms()
        ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->computeFullSlug();

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->computeFullSlug();
        $entityInstance->cascadeFullSlug();
        parent::updateEntity($entityManager, $entityInstance);
    }
}
