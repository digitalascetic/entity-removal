<?php


namespace DigitalAscetic\EntityRemovalBundle\Entity;


interface EntityRemovalDependencyInterface
{

    /**
     * Return classes that depend on the current entity which implements interface
     *
     * @return array
     */
    public function getClassDependencies();
}
