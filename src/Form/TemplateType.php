<?php

namespace VladX\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use VladX\PagesBundle\Dto\TemplateDto;
use VladX\PagesBundle\Utility\PagesTemplates;

class TemplateType extends AbstractType
{
    /**
     * @param PagesTemplates<array<string,array{path:string,form:string}>> $templateHelper
     */
    public function __construct(private readonly PagesTemplates $templateHelper)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder->add('template', ChoiceType::class, [
            'choices' => $this->templateHelper->templatesAsChoices(),
            'required' => false,
            'empty_data' => null,
            'attr' => [
                'data-ea-widget' => 'ea-autocomplete',
            ],
        ]);

        $builder->addDependent('templateData', ['template'], function (DependentField $field, ?string $value) {
            if (null === $value || !$this->templateHelper->getTemplateFormTypeByPath($value)) {
                return;
            }

            $field->add($this->templateHelper->getTemplateFormTypeByPath($value));
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => TemplateDto::class,
        ]);
    }
}
