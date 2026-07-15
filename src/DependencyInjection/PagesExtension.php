<?php

namespace VladX\PagesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PagesExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(\dirname(__DIR__) . '/../config')
        );
        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $builder): void
    {
        /** @var string $projectDir */
        $projectDir = $builder->getParameter('kernel.project_dir');

        $bundleTemplatesOverrideDir = $projectDir.'/templates/bundles/PagesBundle/';
        $bundleTemplatesPath = \dirname(__DIR__).'/../templates/';
        $builder->prependExtensionConfig('twig', [
            'paths' => is_dir($bundleTemplatesOverrideDir)
                ? [
                    'templates/bundles/PagesBundle/' => 'vxpgs',
                    $bundleTemplatesPath => 'vxpgs',
                ]
                : [
                    $bundleTemplatesPath => 'vxpgs',
                ],
        ]);

        $builder->prependExtensionConfig(name: 'vich_uploader', config: [
            'mappings' => [
                'metaimage' => [
                    'uri_prefix' => '/images/meta',
                    'upload_destination' => '%kernel.project_dir%/public/images/meta',
                    'namer' => 'Vich\UploaderBundle\Naming\SmartUniqueNamer',
                ],
            ]
        ]);
    }
}
