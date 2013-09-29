<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;
/**
 * @entity Docmodule
 * @table (name="module")
 */
class DocModule extends Module 
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
     * @param int $page
     * @param int $limit
     * @param string $query
     * @param string $orderBy
     * @param string $sort
     * @return \Zend\Paginator\Paginator
     */
    public function pagination($page, $limit, $query = null, $orderBy = null, $sort = 'ASC') 
    {          
        $data = $this->search($page, $limit, $query, $orderBy, $sort);
        
        return $this->_paginate($data, $page, $limit);      
    }
     /**
     * 
     * @param int $page
     * @param int $limit
     * @param string $query
     * @param string $orderBy
     * @param string $sort
     * @return \Zend\Paginator\Paginator
     */
    public function search($page, $limit =15, $query = null, $orderBy = null, $sort = 'DESC')
    {
     /** @var qb Doctrine\ORM\QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        if($orderBy) {
            $qb->orderBy('m.'.$orderBy, 'ASC');
        }
         // add where to retrict the search
        if ($query) {
            $where = $qb->expr()->orx(
                                  $qb->expr()->like('m.name', '%:query%'), 
                                  $qb->expr()->like('m.description', '%:query%')
                    );
            $qb->add('where',$where);
            $qb->setParameter('query', $query);
        }
         
         /** @var q \Doctrine\ORM\Query */
        $q = $this->setQueryLimits($qb, $page, $limit);
        
        $result = $q->getResult();
        $this->postRead($result);
        
        return $result;
               
    }
    /**
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int $page
     * @param int $limit
     * @return \Doctrine\ORM\Query
     */
    public function setQueryLimits( \Doctrine\ORM\QueryBuilder $qb, $page, $limit)
    {
        list($maxResults, $offset) = $this->limits($page, $limit);
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        
        $q->setMaxResults($maxResults);
        $q->setFirstResult($offset);
        
        return $q;
    }
    /**
     * 
     * calculate the offset and upper limit // return array($maxResults, $offset);
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function limits($page, $limit)
    {
        $page = (int) $page;
        $limit = (int) $limit;
        
        $maxResults = $page * $limit;
        $offset = ($page -1) * $limit;
        
        return array($maxResults, $offset);
    }

    /**
     * @var string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        return $qb->add('select', 'm')
                  ->add('from', "ZfModule\Entity\Module m $columns");
    }
    /**
     * 
     * @param array $data
     * @param int $page
     * @param int $limit
     * @return \Zend\Paginator\Paginator
     */
    public function _paginate(array $data, $page, $limit)
    {
        $page = (int) $page;
        $limit = (int) $limit;
         //instanciate adapter and paginator 
        $adapter = new \Zend\Paginator\Adapter\ArrayAdapter($data);
        $paginator = new \Zend\Paginator\Paginator($adapter);
         //set pagination values
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
     
        return $paginator;
    }
    /**
     * 
     * @param array $array
     * @return array
     */
    public function findByInAsArray(array $array, $sort)
    {   
        if(count($array)){
        $qb = $this->getBaseQueryBuilder();
        $qb->add('where', $qb->expr()->in('m.id', $array));
        $qb->add('orderBy' , 'm.watched DESC');
        return $qb->getQuery()->getArrayResult();
        } else {
            return $array;
        }
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
            $qb->orderBy('m.'.$orderBy , $sort);
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
     * 
     * @param string $owner
     * @return array
     */
    public function findByOwner($owner, $limit= null, $orderBy = null, $sort = 'ASC') 
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        
         // why we are here
        $qb->where('m.owner = :owner');
        $qb->setParameter('owner',$owner);
        
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
     * 
     * @param string $name
     * @return \ZfModule\Entity\Module
     * @throws Exception @todo
     */
    public function findByName($name) {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        
          // why we are here
        $qb->where('m.name = :name');
        $qb->setParameter('name',$name);
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch ( \Doctrine\ORM\NoResultException $exc) {
           return 0;
        }

        
        $this->postRead($result);
        
        return $result;
    }
      /**
     * 
     * @param string $url
     * @return \ZfModule\Entity\Module
     * @throws Exception @todo
     */
    public function findByUrl($url) 
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
         
          // why we are here
        $qb->where('m.url = :url');
        $qb->setParameter('url',$url);
       
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch ( \Doctrine\ORM\NoResultException $exc) {
           return 0;
        }

        $this->postRead($result);
       
        return $result;
    }
    /**
     * 
     * @param string $id
     * @return \ZfModule\Entity\Module
     * @throws Exception @todo
     */
    public function findById($id) 
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        
         // why we are here
        $qb->where('m.id = :id');
        $qb->setParameter('id',$id);
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch ( \Doctrine\ORM\NoResultException $exc) {
           return 0;
        }

        $this->postRead($result);
        
        return $result;        
    }
    /**
     * 
     * @param object $entity
     * @return object
     */
    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null) 
    {
         $this->persist($entity);
         $this->postInsert($entity);
         return $this;
    }
     /**
     * 
     * @param object $entity
     * @return object
     */
    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null) 
    {
        return $this->persist($entity);
    }
     /**
     * 
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
    public function delete($entity, $where = NULL, $tableName = NULL)
    {
       $this->em->remove($entity);
       $this->em->flush();
    }
     /**
     * 
     * @param object $entity
     * @return object
     */
    protected function postRead($result)
    {
        $this->getEventManager()->trigger('find', $this, array('entity' => $result));
    }
      /**
     * 
     * @param object $entity
     * @return object
     */
    protected function postInsert($result)
    {
        $this->getEventManager()->trigger('post-insert', $this, array('entity' => $result));
    }

}