<?php

namespace VladX\PagesBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use VladX\PagesBundle\EventListener\ClassMetadataListener;

class PagesBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('templates')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('form')
                            ->end()
                        ->end()
                ->end()
            ->end()
        ;
    }


    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function prependExtension(ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        /** @var string $projectDir */
        $projectDir = $container->getParameter('kernel.project_dir');

        $bundleTemplatesOverrideDir = $projectDir.'/templates/bundles/PagesBundle/';
        $bundleTemplatesPath = \dirname(__DIR__).'/templates/';
        $container->prependExtensionConfig('twig', [
            'paths' => is_dir($bundleTemplatesOverrideDir)
                ? [
                    'templates/bundles/PagesBundle/' => 'vxpgs',
                    $bundleTemplatesPath => 'vxpgs',
                ]
                : [
                    $bundleTemplatesPath => 'vxpgs',
                ],
        ]);

        $container->prependExtensionConfig(name: 'vich_uploader', config: [
            'mappings' => [
                'metaimage' => [
                    'uri_prefix' => '/images/meta',
                    'upload_destination' => '%kernel.project_dir%/public/images/meta',
                    'namer' => 'Vich\UploaderBundle\Naming\SmartUniqueNamer',
                ],
            ]
        ]);
    }

    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $configurator->services()
            ->set('VladX\PagesBundle\Utility\PageHelper')->autowire(true)
            ->set('VladX\PagesBundle\Utility\PagesTemplates')->args([$config['templates']])->autowire(true)
            ->set('VladX\PagesBundle\Form\TemplateType')->autowire(true)->tag('form.type')
            ->set(ClassMetadataListener::class)->autoconfigure()
        ;
    }
}
