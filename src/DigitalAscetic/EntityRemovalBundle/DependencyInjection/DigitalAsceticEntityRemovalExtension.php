<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 04/03/18
 * Time: 11:40
 */

namespace DigitalAscetic\EntityRemovalBundle\DependencyInjection;


use DigitalAscetic\EntityRemovalBundle\EventListener\EntityRemovalSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

class DigitalAsceticEntityRemovalExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $config = $this->processConfiguration(new Configuration(), $configs);

        if ($config['enabled']) {
            $entityRemovalSubscriber = new Definition(EntityRemovalSubscriber::class);
            $entityRemovalSubscriber->addArgument(new Reference('doctrine.orm.entity_manager'));
            $entityRemovalSubscriber->addArgument(new Reference('logger'));
            $entityRemovalSubscriber->addTag('doctrine.event_subscriber');
            $entityRemovalSubscriber->setPublic(false);
            $container->setDefinition('digital_ascetic.entity_removal.subscriber', $entityRemovalSubscriber);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {

    }

}
