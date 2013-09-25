<?php

namespace ZfModule\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ModuleComment
 *
 * @author gerard
 */
class Comment {
    protected $hasParent;
    protected $parent;
    
    protected $title;
    protected $children;
    protected $id;
    protected $moduleId;
    protected $user;
    protected $comment;
    protected $createdAt;
    //protected $updatedAt;
    public function __construct() {
        // $this->parent = new \ArrayObject;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getChildren(){
        return $this->children;
    }
    public function setChildren($children){
        $this->children = $children;
        return $this;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getParent(){
        return $this->parent;
    }
    public function getChildComments(){
        return $this->childComments;
    }
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
    public function getUser(){
       return $this->user; 
    }
    public function setUser($user){
        $this->user = $user;
        return $this;
    }
    public function getComment(){
       return $this->comment;
    }
    public function setComment($comment){
        $this->comment=$comment;
        return $this;
    }
    public function getCreatedAt(){
       return $this->createdAt;
    }
    public function setCreatedAt($createdAt){
        $this->createdAt;
        return $this;
    }
    public function setParent($parent){
        $this->parent=$parent;
        return $this;
    }
    /**
     * 
     * @param int $hasParent
     */
    public function setHasParent($hasParent){
        $this->hasParent = $hasParent;
        return $this;
    }
    public function getHasParent(){
        return $this->hasParent;
    }
    
}

?>
