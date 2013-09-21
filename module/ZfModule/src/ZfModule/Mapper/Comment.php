<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of Comment
 *
 * @author gerard
 */
class Comment {
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
      /**
     * 
     * @param object $entity
     * @return object
     */
    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null) {
        return $this->persist($entity);
    }
     /**
     * 
     * @param object $entity
     * @return object
     */
    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null) {
        return $this->persist($entity);
    }
     /**
     * 
     * @param object $entity
     * @return object
     */
    protected function persist($entity) {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
    public function delete($entity)
    {
        return $this->em->remove($entity);
    }

    /**
     * 
     * @param object $entity
     * @return object
     */
    protected function postRead($result){
        $this->getEventManager()->trigger('find', $this, array('entity' => $result));
    }
        /**
     * @var string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        return $qb->add('select', 'c')
                  ->add('from', "ZfModule\Entity\Comment c $columns");
    }
}

?>
