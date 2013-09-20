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
class DocModule extends Module {

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
    public function __construct(EntityManager $em, ModuleOptions $options) {
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
    public function search($page, $limit, $query = null, $orderBy = null, $sort = 'ASC')
    {
        $options = new \ZfModule\Options\SearchOptions();
       
         /** @var qb Doctrine\ORM\QueryBuilder */
        $qb = $this->getBaseQueryBuilder();
        
         // add where to retrict the search
        if ($query) {
            $qb->add('where', $qb->expr()->orx(
                                  $qb->expr()->like('m.name', ':query'), 
                                  $qb->expr()->like('m.description', ':query')
                    ));
            $qb->setParameter('query', $query);
        }
         /** @var q \Doctrine\ORM\Query */
        $q = $this->setQueryLimits($qb, $page, $limit);
        
        return $q->getResult();
    }
    /**
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int $page
     * @param int $limit
     * @return \Doctrine\ORM\Query
     */
    public function setQueryLimits( \Doctrine\ORM\QueryBuilder $qb, $page, $limit){
       list($maxResults, $offset) = $this->limits($page, $limit);
        $q = $qb->getQuery();
        /** @var q \Doctrine\ORM\Query */
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
    public function limits($page, $limit){
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
    public function _paginate(array $data, $page, $limit){
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
    public function findAll($limit= null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
      
        if($orderBy) {
            $qb->orderBy($orderBy . ' ' . $sort);
        }

        if($limit) {
            $select->limit($limit);
        }

        $entity = $this->select($select);
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }
    public function findByEmail($email) {
        $er = $this->em->getRepository($this->options->getModuleEntityClass());

        return $er->findOneBy(array('email' => $email));
    }

    public function findByUsername($username) {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findOneBy(array('username' => $username));
    }

    public function findById($id) {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->find($id);
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null) {
        return $this->persist($entity);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null) {
        return $this->persist($entity);
    }

    protected function persist($entity) {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

}