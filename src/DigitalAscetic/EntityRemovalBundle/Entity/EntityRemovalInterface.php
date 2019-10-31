<?php


namespace DigitalAscetic\EntityRemovalBundle\Entity;


interface EntityRemovalInterface
{
    /**
     * SQL Statement to execute before remove entity
     *
     * @return string
     */
    public function getRemovalSQLStatement();
}
