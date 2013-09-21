<?php

namespace ZfModule\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ModuleComment
 *
 * @author gerard
 */
class Comment {
    protected $id;
    protected $moduleId;
    protected $userId;
    protected $comment;
    protected $createdAt;
    //protected $updatedAt;
    
    public function getId(){
       return $this->id; 
    }
    
    public function setId($moduleCommentId){
        $this->id;
        return $this;
    }
    public function getModuleId (){
       return $this->moduleId;
    }
    public function setModuleId ($moduleId){
        $this->moduleId = $moduleId;
        return $this;
    }
    public function getUserId(){
       return $this->userId; 
    }
    public function setUserId($userId){
        $this->userid = $userId;
        return $this;
    }
    public function getComment(){
       return $this->comment;
    }
    public function setComment($comment){
        $this->comment;
        return $this;
    }
    public function getCreatedAt(){
       return $this->createdAt;
    }
    public function setCreatedAt($createdAt){
        $this->createdAt;
        return $this;
    }
    
}

?>
