<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 26/03/18
 * Time: 23:06
 */

namespace DigitalAscetic\EntityRemovalBundle\Test\Functional\Removal;


use DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity2;
use DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity1;
use DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity3;
use DigitalAscetic\EntityRemovalBundle\Test\Entity\Entity4;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class RemovalEntitiesTest extends KernelTestCase
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /** @var Entity1 */
    private $entity1;

    /** @var Entity2 */
    private $entity2;

    /** @var Entity3 */
    private $entity3;

    /** @var Entity4 */
    private $entity4;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {

        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir() . '/DigitalAsceticEntityRemovalBundle');

        self::bootKernel();

        $this->importDatabaseSchema();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->entity1 = new Entity1();
        $this->em->persist($this->entity1);

        $this->entity2 = new Entity2();
        $this->entity2->setEntity1($this->entity1);
        $this->em->persist($this->entity2);

        $this->entity3 = new Entity3();
        $this->entity3->setEntity2($this->entity2);
        $this->em->persist($this->entity3);

        $this->entity4 = new Entity4();
        $this->entity4->setEntity2($this->entity2);
        $this->em->persist($this->entity4);

        $this->em->flush();
    }


    public function testSimpleEntityRemovalPersist()
    {
        $this->em->remove($this->entity1);
        $this->em->flush();
        $this->assertTrue($this->entity4->isNotPersisted());
        $this->assertTrue($this->entity3->isNotPersisted());
        $this->assertTrue($this->entity2->isNotPersisted());
        $this->assertTrue($this->entity1->isNotPersisted());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    private function importDatabaseSchema()
    {
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $schemaTool->dropDatabase();
            $schemaTool->createSchema($metadata);
        }
    }


}
