<?php

namespace ZfModule\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ModuleComment
 *
 * @author gerard
 */
class Comment 
{
    /**
     * binary
     * @var int
     */
    protected $hasParent;
    /**
     *
     * @var \ZfModule\Entity\Comment
     */
    protected $parent;
    /**
     *
     * @var string
     */
    protected $title;
    /**
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    protected $children;
    /**
     *
     * @var int
     */
    protected $id;
    /**
     *
     * @var int
     */
    protected $moduleId;
    /**
     *
     * @var \ZfcUserDoctrineORM\Entity\User
     */
    protected $user;
    /**
     *
     * @var string
     */
    protected $comment;
    /**
     *
     * @var \Datetime
     */
    protected $createdAt;
    /**
     * $this->children = new ArrayCollection;
     * @return \ZfModule\Entity\Comment
     */
    public function __construct() {
        $this->children = new ArrayCollection;
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }
    /**
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildren(){
        return $this->children;
    }
    /**
     * 
     * @param Doctrine\Common\Collections\ArrayCollection $children
     * @return \ZfModule\Entity\Comment
     */
    public function setChildren($children){
        $this->children = $children;
        return $this;
    }
    /**
     * 
     * @param string $title
     * @return \ZfModule\Entity\Comment
     */
    public function setTitle($title){
        $this->title = $title;
        return $this;
    }
    /**
     * 
     * @return \ZfModule\Entity\Comment
     */
    public function getParent(){
        return $this->parent;
    }
    /**
     * 
     * @return int
     */
    public function getId(){
       return $this->id; 
    }
    /**
     * 
     * @param int $moduleCommentId
     * @return \ZfModule\Entity\Comment
     */
    public function setId($moduleCommentId){
        $this->id;
        return $this;
    }
    /**
     * 
     * @return int
     */
    public function getModuleId (){
       return $this->moduleId;
    }
    /**
     * 
     * @param int $moduleId
     * @return \ZfModule\Entity\Comment
     */
    public function setModuleId ($moduleId){
        $this->moduleId = $moduleId;
        return $this;
    }
    /**
     * 
     * @return type  \ZfcUserDoctrineORM\Entity\User
     */
    public function getUser(){
       return $this->user; 
    }
    /**
     * 
     * @param  \ZfcUserDoctrineORM\Entity\User $user
     * @return \ZfModule\Entity\Comment
     */
    public function setUser($user){
        $this->user = $user;
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getComment(){
       return $this->comment;
    }
    /**
     * 
     * @param string $comment
     * @return \ZfModule\Entity\Comment
     */
    public function setComment($comment){
        $this->comment=$comment;
        return $this;
    }
    /**
     * 
     * @return \Datetime
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }
    /**
     * 
     * @param type $createdAt
     * @return \ZfModule\Entity\Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt;
        
        return $this;
    }
    /**
     * 
     * @param \ZfModule\Entity\Comment $parent
     * @return \ZfModule\Entity\Comment
     */
    public function setParent($parent)
    {
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
    /**
     * binary 
     * @return int
     */
    public function getHasParent(){
        return $this->hasParent;
    }
    
}

?>
