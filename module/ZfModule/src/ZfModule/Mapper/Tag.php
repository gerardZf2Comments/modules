<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * tag mapper
 * @todo implement events from abstract class or trait or something
 */
class Tag  
{
    
    /**
     * set in constructor
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * set in contructor
     * @var \ZfcUserDoctrineORM\Options\ModuleOptions
     */
    protected $options;

      /**
     * set up with stuff
     * @param \Doctrine\ORM\EntityManager $em
     * @param \ZfModule\Options\ModuleOptions $options
     */
    public function __construct(EntityManager $em, ModuleOptions $options) 
    {
        $this->em = $em;
        $this->options = $options;
    }
    /**
     * pesist and post-insert event
     * @param object $entity
     * @return object
     * @todo Description
     */
    public function insert($entity) 
    {
        return $this->persist($entity);
    }
     /**
     * update needs an event
     * @param object $entity
     * @return object
     */
    public function update($entity) 
    {
        return $this->persist($entity);
    }
     /**
     * persist and fluch
     * @param object $entity
     * @return object
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
     /**
     * removes an entity
      * @param \ZfModule\Entity\Tag $entity
     */
    public function delete($entity)
    {
       $this->em->remove($entity);
       $this->em->flush();
    }

     /**
      * use alias t for tag entity
     * @param string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        $qb->add('select', 't')
           ->add('from', "ZfModule\Entity\Tag t $columns");
    
        return $qb;
    }
    /**
     * find all
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
            $qb->orderBy($orderBy . ' ' . $sort);
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
     * preforms a like ':query%' search
     * @param string $query
     * @param int $limit
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function findByStarts($query, $limit = null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
       
       //main part of function 
       $qb->add('where', $qb->expr()->like('t.tag', ':query'));
       $query = $query.'%';
       $qb->setParameter('query', $query);
      
       if($orderBy) {
            $select->order($orderBy , $sort);
        }
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if($limit) {          
            $q->setMaxResults($limit);
        } 
        
        $result = $q->getResult();
        
       
        return $result;
    }
     /**
     * gets a single result but doesn't catch the exception
     * @param string $tag
     * @return \ZfModule\Entity\Tag
     * @throws Exception @todo
     */
    public function findByTag($tag)
    {
        /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        $qb->where('t.tag = :tag');
        $qb->setParameter('tag', $tag);              
        $result = $qb->getQuery()->getSingleResult();
        //$this->postRead($result);
       
        return $result;
    }
    public function findOrCreate($tag)
    {
        try {
            return $this->findByTag($tag);
        
        } catch (\Doctrine\ORM\NoResultException $exc) {
            return $this->create($tag);
        }
    }
    public function create($tag)
    {
        $entity = new \ZfModule\Entity\Tag;
        $entity->setTag($tag);
        
        $this->insert($entity);
        
        return $entity;
    }
}
