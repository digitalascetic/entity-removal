<?php


namespace DigitalAscetic\EntityRemovalBundle\Test\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class Entity4
 * @package DigitalAscetic\EntityRemovalBundle\Test\Entity
 *
 * @ORM\Table(name="entity4")
 * @ORM\Entity()
 */
class Entity4
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
     * @var Entity2 $entity2
     *
     * @ORM\ManyToOne(targetEntity="DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity2")
     * @ORM\JoinColumn(name="entity2_id", referencedColumnName="id", nullable=false)
     */
    private $entity2;

    /**
     * @return Entity2
     */
    public function getEntity2(): Entity2
    {
        return $this->entity2;
    }

    /**
     * @param Entity2 $entity2
     */
    public function setEntity2(Entity2 $entity2): void
    {
        $this->entity2 = $entity2;
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
}
