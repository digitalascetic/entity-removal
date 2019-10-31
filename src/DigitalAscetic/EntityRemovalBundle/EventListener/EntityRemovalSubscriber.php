<?php


namespace DigitalAscetic\EntityRemovalBundle\EventListener;


use DigitalAscetic\EntityRemovalBundle\Entity\EntityRemovalDependencyInterface;
use DigitalAscetic\EntityRemovalBundle\Entity\EntityRemovalInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Psr\Log\LoggerInterface;

class EntityRemovalSubscriber implements EventSubscriber
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var Connection */
    private $conn;

    /** @var LoggerInterface */
    private $logger;

    /** @var UnitOfWork */
    private $uow;

    /**
     * EntityRemovalSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
    }


    public function getSubscribedEvents()
    {
        return array(
            Events::onFlush
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        foreach ($this->uow->getScheduledEntityDeletions() as $entity) {
            // For current remove entity, we avoid duplicate remove calling method, otherwise infinite loop occurs
            $this->doRemovalDependency($entity, false);
        }
    }

    private function doRemovalDependency($entity, $doRemove = true)
    {
        /** @var EntityRemovalDependencyInterface $entity */
        if ($this->isEntityRemovalDependency($entity)) {
            $dependencies = $entity->getClassDependencies();

            $entityClassName = $this->getClassName($entity);

            foreach ($dependencies as $dependency) {
                $properties = $this->getAssociationsNamesByTargetClass($dependency, $entityClassName);

                foreach ($properties as $property) {
                    $entitiesRelated = $this->em->getRepository($dependency)->findBy(array($property => $entity->getId()));

                    foreach ($entitiesRelated as $obj) {
                        $this->doRemovalDependency($obj);
                    }
                }
            }
        }

        if ($doRemove) {
            $this->doRemoval($entity);
        }
    }

    private function doRemoval($entity)
    {
        /** @var EntityRemovalInterface $entity */
        if ($this->isEntityRemoval($entity)) {
            try {
                $this->conn->executeQuery($entity->getRemovalSQLStatement());
            } catch (\Exception $exception) {
                $this->logger->error('[DigitalAsceticEntityRemoval] Error executing removal sql statement: ' . $exception->getMessage());
            }
        }

        $this->em->remove($entity);

        $entityClassName = $this->getClassName($entity);
        $metadataEntity = $this->em->getClassMetadata($entityClassName);
        $this->uow->computeChangeSet($metadataEntity, $entity);
    }

    private function getAssociationsNamesByTargetClass($entity, $className)
    {
        $dependencyClassName = $this->getClassName($entity);
        $metadata = $this->em->getClassMetadata($dependencyClassName);
        $propertiesMapping = $metadata->getAssociationsByTargetClass($className);
        return array_keys($propertiesMapping);

    }

    private function getClassName($entity): string
    {
        $reflectionDependencyClass = new \ReflectionClass($entity);
        return $reflectionDependencyClass->getName();
    }

    private function isEntityRemovalDependency($entity)
    {
        return ($entity instanceof EntityRemovalDependencyInterface);
    }

    private function isEntityRemoval($entity)
    {
        return ($entity instanceof EntityRemovalInterface);
    }
}
