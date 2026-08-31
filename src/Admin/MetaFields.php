<?php

namespace VladX\PagesBundle\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use VladX\PagesBundle\Form\MetaPropertyType;

class MetaFields
{
    public static function getSeoTab(): array
    {
        return array_merge(
            [
                FormField::addTab('SEO'),
            ],
            self::getMetaFields()
        );
    }

    public static function getMetaFields(): array
    {
        return [
            TextField::new('meta.metaTitle', 'Meta title')->hideOnIndex(),
            TextareaField::new('meta.metaDescription', 'Meta description')->hideOnIndex(),
            // TextField::new('metaKeywords', 'Meta keywords')->hideOnIndex(),
            FormField::addFieldset('Social media share'),
            TextField::new('meta.ogTitle', 'Opengraph title (Optional)')
                ->setHelp('Title used to generate snippet during share in social media (X, Facebook, etc.). By default equals meta title')
                ->hideOnIndex(),
            TextareaField::new('meta.ogDescription', 'Opengraph description (Optional)')
                ->setHelp('description used to generate snippet during share social media (X, Facebook, etc.). By default equals meta description')
                ->hideOnIndex(),
            VichImageField::new('metaOgImageFile', 'Opengraph image (Optional)')->hideOnIndex()
                ->setHelp('1200x630 pixels size is recommended by Facebook'),
            CollectionField::new('meta.metaProperties')->hideOnIndex()
                ->setRequired(false)
                ->setEntryType(MetaPropertyType::class)
            ,
            FormField::addFieldset('Robots tag'),
            ChoiceField::new('meta.robots')
                ->setHelp('More about robots meta tag <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/meta/name/robots" target="_blank">link</a>')
                ->allowMultipleChoices(true)->setChoices([
                    'index' => 'index',
                    'noindex' => 'noindex',
                    'follow' => 'follow',
                    'nofollow' => 'nofollow',
                    'none' => 'none',
                    'noarchive' => 'noarchive',
                    'nosnippet' => 'nosnippet',
                    'noimageindex' => 'noimageindex',
                    'nocache' => 'nocache',
                ]),
        ];
    }
}
