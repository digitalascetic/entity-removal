<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 04/03/18
 * Time: 11:38
 */

namespace DigitalAscetic\EntityRemovalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('digital_ascetic_entity_removal');

        $rootNode
            ->canBeEnabled()
        ->end();

        return $treeBuilder;

    }

}
