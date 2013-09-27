<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;


class Tag  
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
     * @var string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        return $qb->add('select', 'm')
               ->add('from', "ZfModule\Entity\Tag m $columns");
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
     * @param type $limit
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function findByStarts($query, $limit = null, $orderBy = null, $sort = 'ASC')
    {
         /** @var qb Doctrine/ORM/QueryBuilder */
       $qb = $this->getBaseQueryBuilder();
       
       //main part of function 
       $qb->add('where', $qb->expr()->like('m.tag', ':query'));
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
        $this->postRead($result);
       
        return $result;
    }

     /**
     * 
     * @param string $tag
     * @return \ZfModule\Entity\Tag
     * @throws Exception @todo
     */
    public function findByTag($tag)
    {
        /** @var qb Doctrine/ORM/QueryBuilder */
        $qb = $this->getBaseQueryBuilder();

        $qb->add('where', $qb->expr()->eq('tag', $tag));
                       
        $result = $qb->getQuery()->getSingleResult();
        $this->postRead($result);
       
        return $result;
    }
    /**
     * 
     * @param int $id
     * @return ZfModule\Entity\Tag
     */
    public function findById($id)
    {
        $sql = $this->getSql();
        $select = $sql->select()
                       ->from($this->tableName)
                       ->where(array('id' => $id));

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        
        return $entity;
    }
    public function postRead(){
        
    }
    
}
