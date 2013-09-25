<?php

namespace ZfModule\Mapper;

use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;
use ZfModule\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;


class Tag  //implements ModuleInterface
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
     * @var string $columns
     * @return Doctrine/ORM/QueryBuilder 
     */
    public function getBaseQueryBuilder($columns = '')
    {
        $qb = $this->em->createQueryBuilder();
       
        return $qb->add('select', 'm')
               ->add('from', "ZfModule\Entity\Tag m $columns");
    }
    public function pagination($page, $limit, $query = null, $orderBy = null, $sort = 'ASC')
    {
        $sql = $this->getSql();
        $select = $sql->select()
            ->from($this->tableName);

        

        
        $adapter = new \Zend\Paginator\Adapter\DbSelect(
            $select,
            $this->getSql(),
            new HydratingResultSet($this->getHydrator(), $this->getEntityPrototype())
        );
        $paginator = new \Zend\Paginator\Paginator($adapter);

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
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
        $this->postRead($result);
        return $result;
    }
    /*
SELECT module_tags.tag AS tag, module_tags.id AS id, COUNT( module_tag.tag_id ) AS tagcount
FROM  `module_tag` 
INNER JOIN  `module_tags` ON  `module_tags`.`id` =  `module_tag`.`tag_id` 
WHERE  `module_tags`.`tag` LIKE  'cu%'
GROUP BY  `module_tag`.`tag_id` 
ORDER BY tagcount DESC 
     */
/**
 * @todo this will be used by a service probably called by a controller with a ajax 
 * @todo request to suggest search terms
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

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity, $tableName, $hydrator);
        $entity->setId($result->getGeneratedValue());
        return $entity;
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (!$where) {
            $where = 'module_id = ' . $entity->getId();
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }

    public function delete($entity, $where = null, $tableName = null)
    {
        if (!$where) {
            $where = 'module_id = ' . $entity->getId();
        }
         return parent::delete($where, $tableName);
    }
    public function postRead(){
        
    }
    
}
