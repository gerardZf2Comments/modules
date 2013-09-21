<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of Comment
 *
 * @author gerard
 */
class Comment {
 
    /**
     * 
     * @param int $userId
     * @param int $moduleId
     * @param string $comment
     * @return type
     * @throws Exc
     */
    public function add($userId, $moduleId, $comment){
        $userId = (int) $userId;
        $moduleId = (int) $moduleId;
        $comment = (string) $comment;
         /** @var \ZfModule\Entity\Comment */
        $commentEntity = $this->getCommentEntity();
        $commentEntity->setUserId($userId);
        $commentEntity->setModuleId($moduleId);
        $commentEntity->setComment($comment);
        
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
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\ModuleSearch
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
        return $this->getServiceLocator()->get('zfmodule_entity_comment');
        
    }

    /**
     * 
     * @return \ZfModule\Entity\Comment
     */
    public function getCommentEntity()
    {
        return $this->getServiceLocator()->get('zfmodule_entity_comment');
    }
   
 }

?>
