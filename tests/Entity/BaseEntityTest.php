<?php


namespace DigitalAscetic\EntityRemovalBundle\Test\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseEntityTest
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
}
