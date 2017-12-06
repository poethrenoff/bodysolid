<?php

namespace AppExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppExtraExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(array(__DIR__ . '/../Resources/config')));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['upload']['entities'] as $class => $entityConfig) {
            foreach ($entityConfig as $field => $fieldConfig) {
                $config['upload']['entities'][$class][$field]['directory'] = $fieldConfig['directory'] ?? $config['directory'];
                $config['upload']['entities'][$class][$field]['alias'] = $fieldConfig['alias'] ?? $config['alias'];
                $config['upload']['entities'][$class][$field]['fileField'] = $fieldConfig['fileField'] ?? ($field . 'File');
            }
        }
        $container->setParameter('upload', $config['upload']);
        $container->setParameter('image', $config['image']);
    }
}
