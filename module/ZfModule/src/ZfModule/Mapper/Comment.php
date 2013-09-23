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
     * @var \ZfcUserDoctrineORM\Options\ModuleOptions
     */
    protected $options;

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \ZfModule\Options\ModuleOptions $options
     */
    public function __construct(EntityManager $em, ModuleOptions $options) 
    {
        $this->em = $em;
        $this->options = $options;
    }
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
      //  $this->getEventManager()->trigger('find', $this, array('entity' => $result));
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
    /**
     * 
     * @param int $limit
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function findAll($limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
      
        if($orderBy) {
            $qb->orderBy('c.'.$orderBy , $sort);
        }
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
        return $result;
    }
    public function findBy($by, $id, $limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
      
        if($orderBy) {
            $qb->orderBy('c.'.$orderBy , $sort);
        }
       
        $qb->where('c.'.$by .'= :id');
        $qb->setParameter('id', $id);
       
        /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
        return $result;
    }
     public function findParentsBy($by, $id, $limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->em->createQueryBuilder();
       
       $qb->add('select', 'c')
                  ->add('from', "ZfModule\Entity\Comment c");
    
        if($orderBy) {
            $qb->orderBy('c.'.$orderBy , $sort);
        }
       
       // $qb->where('c.'.$by .'= :id');
       
        $qb->where('c.hasParent = 0');
       
        $where = $qb->expr()->andX(
                $qb->expr()->eq('c.'.$by , ':id'),
                $qb->expr()->eq('c.hasParent', 0)
                );
        $qb->add('where', $where);
        $qb->setParameter('id', $id);
       // $qb->join('c.childComments', 'comment');
        /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
        return $result;
    }
     
}

?>
