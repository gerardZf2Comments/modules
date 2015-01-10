<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * comment mapper uses doctrine
 * @author gerard
 */
class Comment
{
    /**
     * to be set in factory
     * @var \Doctrine\ORM\EntityManager
     */    
    protected $em;
    
    
    /**
     * to be set in factory
     * @var \ZfcUserDoctrineORM\Options\ModuleOptions
     */
    protected $options;

    /**
     * some params required
     * @param \Doctrine\ORM\EntityManager $em
     * @param \ZfModule\Options\ModuleOptions $options
     */
    public function __construct(EntityManager $em, ModuleOptions $options) 
    {
        $this->em = $em;
        $this->options = $options;
    }
    /**
     * write to db here 
     * @param \ZfModule\Entity\Comment $entity
     * @return \ZfModule\Entity\Comment
     */
    public function insert($entity) 
    {
        return $this->persist($entity);
    }
     /**
     * write to db here 
     * @param \ZfModule\Entity\Comment $entity
     * @return \ZfModule\Entity\Comment
     */
    public function update($entity) {
        return $this->persist($entity);
    }
     /**
     * write to db here 
     * @param \ZfModule\Entity\Comment $entity
     * @return \ZfModule\Entity\Comment
     */
    protected function persist($entity) {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
     /**
     * Removes an entity
     * @param \ZfModule\Entity\Comment $entity
     * @throws ORMInvalidArgumentException
     */
    public function delete($entity)
    {
       $this->em->remove($entity);
       $this->em->flush();
    }

    /**
     * fire events for listeners
     * @param  \ZfModule\Entity\Comment $entity
     * @todo implement this
     */
    protected function postRead($result)
    {
      //  $this->getEventManager()->trigger('find', $this, array('entity' => $result));
    }
     /**
     * gotta format colums properly eg. "c.xgy, c.xxx"
     * @param string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        $qb->add('select', 'c')
           ->add('from', "ZfModule\Entity\Comment c $columns");
        
        return $qb;
    }
    /**
     * find all fires a not implemented event
     * @param int $limit
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function findAll($limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
      
        if ($orderBy) {
            $qb->orderBy('c.'.$orderBy, $sort);
        }
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if ($limit) {          
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
     * @return array
     */
    public function findBy($by, $id, $limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
      
        if ($orderBy) {
            $qb->orderBy('c.'.$orderBy, $sort);
        }
       
        $qb->where('c.'.$by .'= :id');
        $qb->setParameter('id', $id);
       
        /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery(); 
        if ($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
        
        return $result;
    }
    /**
     * finds only elements that are parents with a user specified where clause
     * @param string $where
     * @param mixed $id
     * @param int $limit
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function findParentsWhere($where, $id, $limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->em->createQueryBuilder();
       
       $qb->add('select', 'c')
                  ->add('from', "ZfModule\Entity\Comment c");
    
        if ($orderBy) {
            $qb->orderBy('c.'.$orderBy , $sort); 
        }
       
       // $qb->where('c.'.$by .'= :id');
       
        $qb->where('c.hasParent = 0');
       
        $where = $qb->expr()->andX(
            $qb->expr()->eq('c.'.$where, ':id'),
            $qb->expr()->eq('c.hasParent', 0)
        );
        $qb->add('where', $where);
        $qb->setParameter('id', $id);
       
        /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if ($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
       
        return $result;
    }
    /**
     * uses options
     * @return string
     */
    public function getCommentEntityClass()
    {
        return $this->options->getCommentEntityClassName();
    }
    /**
     * uses options
     * @return string
     */
    public function getUserEntityClass()
    {
        return $this->options->getUserEntityClassName();
    }
    /**
     * get the em set in contructor
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}
