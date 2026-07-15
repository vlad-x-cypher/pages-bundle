<?php

namespace VladX\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use VladX\PagesBundle\Entity\Meta;

class MetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('metaTitle', TextType::class, [
                'label' => 'meta title',
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'meta description',
            ])
            # ->add('metaKeywords', TextareaType::class, [
            #    'label' => 'meta description',
            # ])
            ->add('ogTitle', TextType::class, [
                'label' => 'og:title',
                'help' => 'Title used to generate snippet during share in social media (X, Facebook, etc.). By default equals meta title',
            ])
            ->add('ogDescription', TextareaType::class, [
                'label' => 'og:description',
                'help' => 'description used to generate snippet during share social media (X, Facebook, etc.). By default equals meta description'
            ])
            ->add('ogImageFile', VichImageType::class, [
                'label' => 'og:image',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
                'help' => '1200x630 pixels size is recommended by Facebook',
            ])
            ->add('metaProperties', CollectionType::class, [
                'label' => 'Meta properties',
                'entry_type' => MetaPropertyType::class,
                'allow_delete' => true,
                'allow_add' => true,
                'help' => 'additional meta properties',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meta::class,
        ]);
    }
}
