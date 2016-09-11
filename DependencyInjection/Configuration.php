<?php

namespace Irozgar\GulpRevVersionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    const DEFAULT_MANIFEST_PATH = 'Resources/assets/rev-manifest.json';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('irozgar_gulp_rev_versions');

        $rootNode
            ->children()
                ->scalarNode('manifest_path')
                    ->treatNullLike(self::DEFAULT_MANIFEST_PATH)
                    ->defaultValue(self::DEFAULT_MANIFEST_PATH)
                ->end()
                ->booleanNode('replace_default_version_strategy')
                    ->treatNullLike(true)
                    ->defaultFalse()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
