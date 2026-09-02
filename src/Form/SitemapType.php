<?php

namespace VladX\PagesBundle\Form;

use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use VladX\PagesBundle\Dto\SitemapSettingsDto;

class SitemapType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('isEnabled', CheckboxType::class, [
        ]);

        $builder->add('changeFrequency', ChoiceType::class, [
            'choices' => [
                UrlConcrete::CHANGEFREQ_DAILY => UrlConcrete::CHANGEFREQ_DAILY,
                UrlConcrete::CHANGEFREQ_ALWAYS => UrlConcrete::CHANGEFREQ_ALWAYS,
                UrlConcrete::CHANGEFREQ_HOURLY => UrlConcrete::CHANGEFREQ_HOURLY,
                UrlConcrete::CHANGEFREQ_WEEKLY => UrlConcrete::CHANGEFREQ_WEEKLY,
                UrlConcrete::CHANGEFREQ_MONTHLY => UrlConcrete::CHANGEFREQ_MONTHLY,
                UrlConcrete::CHANGEFREQ_YEARLY => UrlConcrete::CHANGEFREQ_YEARLY,
                UrlConcrete::CHANGEFREQ_NEVER => UrlConcrete::CHANGEFREQ_NEVER,
            ],
            'required' => false,
            'attr' => [
                'data-ea-widget' => 'ea-autocomplete',
            ],
        ]);

        $builder->add('priority', NumberType::class, [
        ]);

        $builder->addViewTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => SitemapSettingsDto::class,
        ]);
    }
    public function transform($value): mixed
    {
        if (null === $value) {
            return null;
        }

        return SitemapSettingsDto::fromArray($value);
    }

    public function reverseTransform($value): mixed
    {
        if (null === $value) {
            return null;
        }

        // Convert the entity object back to its ID for storage
        if ($value instanceof SitemapSettingsDto) {
            return $value->toArray();
        }

        return $value;
    }

}
