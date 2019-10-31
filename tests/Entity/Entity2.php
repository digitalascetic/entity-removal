<?php


namespace DigitalAscetic\EntityRemovalBundle\Test\Entity;


use DigitalAscetic\EntityRemovalBundle\Entity\EntityRemovalDependencyInterface;
use DigitalAscetic\EntityRemovalBundle\Entity\EntityRemovalInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Entity2
 * @package DigitalAscetic\EntityRemovalBundle\Test\Entity
 *
 * @ORM\Table(name="entity2")
 * @ORM\Entity()
 */
class Entity2 implements EntityRemovalDependencyInterface
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
     * @var Entity1 $entity1
     *
     * @ORM\ManyToOne(targetEntity="DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity1")
     * @ORM\JoinColumn(name="entity1_id", referencedColumnName="id", nullable=false)
     */
    private $entity1;

    /**
     * @return Entity1
     */
    public function getEntity1(): Entity1
    {
        return $this->entity1;
    }

    /**
     * @param Entity1 $entity1
     */
    public function setEntity1(Entity1 $entity1): void
    {
        $this->entity1 = $entity1;
    }


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
            Entity3::class,
            Entity4::class
        );
    }
}
