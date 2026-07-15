<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $container->services()->set('VladX\PagesBundle\Utility\PageHelper')->autowire(true);
};
