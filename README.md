# @digitalascetic/entity-removal
===========

This bundle try to solve the problem  when removing entities that has one direction association to other entities.

#### Case study

Suppose we have this two classes:

```
class Object {
     /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;   
}

class Object2 {
 /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ParentEntity $parent
     *
     * @ORM\ManyToOne(targetEntity="Object")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", nullable=false)
     */
    private $parent;
}
```

And we have instantiated this two clases in $object and $object2 respectively with its required properties. 

If we execute:

```
$entityManager->remove($object);
$entityManager->flush();
```

SQL index error is returned because $object2 has a one direction association to $object. 

So first we have to remove $object2, and later we can remove $object.


#### How to solve

To simplify entities relations and remove order, we need to implements EntityRemovalDependencyInterface, so:

```
class Object implements EntityRemovalDependencyInterface {
     /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;   

    public function getClassDependencies()
    {
        return array(
            Object2::class
        );
    }
}
```

Now, when we run:


```
$entityManager->remove($object);
$entityManager->flush();
```
No error occurs, because entity-removal bundle, detect that $object has a class depends on, and it remove $object2 before $object.

#### Bonus track

We have implemented EntityRemovalInterface that need a method called: getRemovalSQLStatement

This is util for example, if we need to update some related entity before remove it.
