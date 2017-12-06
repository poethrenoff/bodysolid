<?php

namespace AppExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_extra');

        $this->addUploadSection($rootNode);

        return $treeBuilder;
    }

    protected function addUploadSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('upload')
                    ->children()
                        ->scalarNode('directory')->isRequired()->end()
                        ->scalarNode('alias')->defaultValue('/upload')->end()
                        ->arrayNode('entities')
                            ->useAttributeAsKey('class')
                            ->prototype('array')
                                ->useAttributeAsKey('field')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('fileField')->end()
                                        ->scalarNode('directory')->end()
                                        ->scalarNode('alias')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('image')
                    ->children()
                        ->scalarNode('web_dir')->defaultValue('%kernel.root_dir%/../web')->end()
                        ->scalarNode('cache_dir')->defaultValue('cache')->end()
                        ->scalarNode('cache_dir_mode')->defaultValue('0775')->end()
                        ->booleanNode('throw_exception')->defaultFalse()->end()
                        ->scalarNode('fallback_image')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
