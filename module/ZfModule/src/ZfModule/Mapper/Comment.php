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
class Comment 
{
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
     /**
     * Removes an entity instance.
     *
     * A removed entity will be removed from the database at or before transaction commit
     * or as a result of the flush operation.
     *
     * @param object $entity The entity instance to remove.
     *
     * @return void
     *
     * @throws ORMInvalidArgumentException
     */
    public function delete($entity)
    {
       $this->em->remove($entity);
       $this->em->flush();
    }

    /**
     * 
     * @param object $entity
     * @return object
     * @todo
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
    
    /**
     * does a where with the main entity.$by = $id
     * @param strin $by
     * @param mixed $id
     * @param int $limit
     * @param string $orderBy
     * @param string $sort
     * @return type
     */
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
    public function getCommentEntityClass(){
        return $this->options->getCommentEntityClassName();
    }
    public function getUserEntityClass(){
        return $this->options->getUserEntityClassName();
    }
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager(){
        return $this->em;
    }
}

?>
