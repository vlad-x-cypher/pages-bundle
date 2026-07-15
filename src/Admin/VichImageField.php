<?php

namespace VladX\PagesBundle\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VichImageField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|string|bool|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@Pages/admin/vich_image.html.twig')
            ->setFormType(VichImageType::class)
            ->addCssClass('field-vich-image')
            ->addJsFiles(
                Asset::fromEasyAdminAssetPackage('field-image.js'),
                Asset::fromEasyAdminAssetPackage('field-file-upload.js'),
            )
        ;
    }
}
