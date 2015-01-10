<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * manages comments coupled with a entity manager +more
 *@todo test
 * @author gerard
 */
class Comment {
 
    /**
     * 
     * @param int $userId
     * @param int $moduleId
     * @param string $comment
     * @param string title
     * @return type
     * @throws Exc
     */
    public function add($userId, $moduleId, $comment, $title){
        $userId = (int) $userId;
        $moduleId = (int) $moduleId;
        $comment = (string) $comment;
        $title = (string)$title;
        // WTF
        $user = $this->getUserById($userId);
         /** @var \ZfModule\Entity\Comment */
        $commentEntity = $this->getCommentEntity();
       // $parent = $this->getCommentEntity();
        $userId = $user->getId();
     
        $userComments =$user->getUserComments();
        
        foreach ($userComments as $userComment) {
           
        }
        $commentEntity->setUser($user);
        // END WTF
        $user->getUserComments()->add($commentEntity);
        $commentEntity->setModuleId($moduleId);
        $commentEntity->setComment($comment);
        $commentEntity->setTitle($title);
    //    $commentEntity->setParent($parent);
        $commentEntity->setHasParent(0);
        return $this->getCommentMapper()->insert($commentEntity);
    }
    public function addReply($userId, $comment, $parentCommentId)
    {
        $userId = (int) $userId;
        $parentCommentId = (int) $parentCommentId;
        $comment = (string) $comment;
        
        
        $commentMapper = $this->getCommentMapper();
        $em = $commentMapper->getEntityManager();
        $parentComment = $em->find( $commentMapper->getCommentEntityClass(), 
                                  $parentCommentId);
        $moduleId = $parentComment->getModuleId();
        $user = $em->find('User\Entity\User', $userId);
        /** @var \ZfModule\Entity\Comment */
        $commentEntity = $this->getCommentEntity();
        $userId = $user->getId();
        $commentEntity->setUser($user);
        $commentEntity->setModuleId($moduleId);
        $commentEntity->setParent($parentComment);
        $commentEntity->setComment($comment);
        $commentEntity->setHasParent(1);
        
        return $this->getCommentMapper()->insert($commentEntity);
    }

    /**
     * 
     * @param int $userId
     * @param int $moduleId
     * @param int $commentId
     * @param string $comment
     * @return type
     * @throws Exc
     */
    public function edit($userId, $moduleId, $commentId, $comment )
    {
        $userId = (int) $userId;
        $moduleId = (int) $moduleId;
        $commentId = (int) $commentId;
        $comment = (int) $comment;
       
        /** @var \ZfModule\Entity\Comment */
        $commentEntity = $this->getCommentMapper()->find($commentId);
        if(!$commentEntity){
            throw new Exception;
        }
        $commentEntity->setUserId($userId);
        $commentEntity->setModuleId($moduleId);
        $commentEntity->setComment($comment);
        
        return $this->getCommentMapper()->update($commentEntity);
    }
    /**
     * 
     * @param int $userId
     * @param int $moduleId
     * @param int $comment
     * @return type
     * @throws Exc
     */
    public function delete($userId, $moduleId, $commentId)
    {
        $userId = (int) $userId;
        $moduleId = (int) $moduleId;
        $commentId = (int) $commentId;
        
        $commentEntity = $this->getCommentMapper()->find($commentId);
        if(!$commentEntity){ 
            throw new Exception;
        }
       
        return $this->getCommentMapper()->delete($commentEntity);
    }
   /**
    * 
    * @param int $moduleId
    * @return array array of comment objects
    */
    public function commentsByModuleId($moduleId, $limit = 15, $sort = null, $order= null)
    {
        return $this->getCommentMapper()->findParentsWhere('moduleId',$moduleId, $limit,  $sort, $order);
    }

    /**
    * 
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\Search\ModuleSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm) 
    {
        $this->serviceLocator = $sm;
        return $this;
    }
    /**
     * 
     * @return \ZfModule\Mapper\Comment
     */
    public function getCommentMapper()
    {
        return $this->getServiceLocator()->get('zfmodule_mapper_comment');
        
    }

    /**
     * 
     * @return \ZfModule\Entity\Comment
     */
    public function getCommentEntity()
    {
        return $this->getServiceLocator()->get('zfmodule_entity_comment');
    }
     /**
     * 
     * @return \ZfcUserDoctrineORM\Entity\User
     */
    public function getUserById($id)
    {
        $commentMapper = $this->getCommentMapper();
        $em = $commentMapper->getEntityManager();
        
        return $em->find( $commentMapper->getUserEntityClass(), 
                                  $id);
    }
   
 }

?>
