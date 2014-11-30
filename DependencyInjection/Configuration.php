<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root(PellrCmsExtension::ALIAS)
            ->children()

                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()

                        ->arrayNode('phpcr')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()

                                ->scalarNode('basepath')->defaultValue(PellrCmsExtension::PHPCR_BASEPATH)->end()
                                ->scalarNode('manager_registry')->defaultValue('doctrine_phpcr')->end()
                                ->scalarNode('manager_name')->defaultNull()->end()
                                ->scalarNode('document_class')->defaultValue('Pellr\CmsBundle\Doctrine\Phpcr\Page')->end()

                                ->enumNode('use_sonata_admin')
                                    ->values(array(true, false, 'auto'))
                                    ->defaultValue('auto')
                                ->end()

                                ->arrayNode('sonata_admin')
                                    ->children()
                                        ->enumNode('sort')
                                            ->values(array(false, 'asc', 'desc'))
                                            ->defaultValue(false)
                                        ->end()
                                    ->end()
                                ->end()

                            ->end() // persistence.phpcr children
                        ->end() // persistence.phpcr
                    ->end() // persistence children
                ->end() // persistence[]

                ->enumNode('use_menu')
                    ->values(array(true, false, 'auto'))
                    ->defaultValue('auto')
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
