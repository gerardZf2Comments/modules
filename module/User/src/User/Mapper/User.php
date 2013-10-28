<?php

namespace User\Mapper;

use ZfcUserDoctrineORM\Mapper\User as UserMapper;

class User extends UserMapper
{
    public function findAll($limit = null, $orderBy=null, $sort=null)
    {
        $qb = $this->getBaseQueryBuilder();
        if ($orderBy) {
            $qb->orderBy('u.'.$orderBy, $sort);
        }
         /** @var q \Doctrine\ORM\Query */
        $q = $qb->getQuery();
        if ($limit) {          
            $q->setMaxResults($limit);
        } 
        $result = $q->getResult();
       // $this->postRead($result);
        return $result;
    }
//    public function findAll($limit= null, $orderBy = null, $sort = 'ASC')
//    {
//        $sql = $this->getSql();
//        $select = $sql->select()
//                       ->from($this->tableName);
//
//        if($orderBy) {
//            $select->order($orderBy . ' ' . $sort);
//        }
//
//        if($limit) {
//            $select->limit($limit);
//        }
//
//        $entity = $this->select($select);
//        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
//        return $entity;
//    }
}
