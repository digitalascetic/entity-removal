<?php


namespace DigitalAscetic\EntityRemovalBundle\Test\Entity;


use DigitalAscetic\EntityRemovalBundle\Entity\EntityRemovalDependencyInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entity1
 * @package DigitalAscetic\EntityRemovalBundle\Test\Entity
 *
 * @ORM\Table(name="entity1")
 * @ORM\Entity()
 */
class Entity1 implements EntityRemovalDependencyInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function isPersisted(): bool
    {
        return is_numeric($this->id);
    }

    public function isNotPersisted(): bool
    {
        return !$this->isPersisted();
    }

    public function getClassDependencies()
    {
        return array(
            Entity2::class
        );
    }
}
