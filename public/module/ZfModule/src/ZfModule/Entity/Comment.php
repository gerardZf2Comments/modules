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
     * parent comment entity
     * @var \ZfModule\Entity\Comment
     */
    protected $parent;
    /**
     *comment tittle not used in replies
     * @var string
     */
    protected $title;
    /**
     *collection of \ZfModule\Entity\Comment
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    protected $children;
    /**
     * discription
     * @var int
     */
    protected $id;
    /**
     *all comments are related to a module 1-1
     * @var int
     */
    protected $moduleId;
    /**
     *comment must have been made by user 1-1
     * @var \ZfcUserDoctrineORM\Entity\User
     */
    protected $user;
    /**
     *discription
     * @var string
     */
    protected $comment;
    /**
     *using doctrine datatimes are persisted and retrieved as objects
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
     * return title if set
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }
    /**
     * collection or proxy
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildren(){
        return $this->children;
    }
    /**
     * collection or proxy
     * @param Doctrine\Common\Collections\ArrayCollection $children
     * @return \ZfModule\Entity\Comment
     */
    public function setChildren($children){
        $this->children = $children;
        return $this;
    }
    /**
     * for non-replies
     * @param string $title
     * @return \ZfModule\Entity\Comment
     */
    public function setTitle($title){
        $this->title = $title;
        return $this;
    }
    /**
     * get parent entity
     * @return \ZfModule\Entity\Comment
     */
    public function getParent(){
        return $this->parent;
    }
    /**
     * get id
     * @return int
     */
    public function getId(){
       return $this->id; 
    }
    /**
     * set id
     * @param int $moduleCommentId
     * @return \ZfModule\Entity\Comment
     */
    public function setId($moduleCommentId){
        $this->id;
        return $this;
    }
    /**
     * get module id
     * @return int
     */
    public function getModuleId (){
       return $this->moduleId;
    }
    /**
     * set module id
     * @param int $moduleId
     * @return \ZfModule\Entity\Comment
     */
    public function setModuleId ($moduleId){
        $this->moduleId = $moduleId;
        return $this;
    }
    /**
     * get user entity
     * @return type  \ZfcUserDoctrineORM\Entity\User
     */
    public function getUser(){
       return $this->user; 
    }
    /**
     * set a user entity 
     * @param  \ZfcUserDoctrineORM\Entity\User $user
     * @return \ZfModule\Entity\Comment
     */
    public function setUser($user){
        $this->user = $user;
        return $this;
    }
    /**
     * get a set comment
     * @return string
     */
    public function getComment(){
       return $this->comment;
    }
    /**
     * set comment
     * @param string $comment
     * @return \ZfModule\Entity\Comment
     */
    public function setComment($comment)
    {
        $this->comment=$comment;
        return $this;
    }
    /**
     * using objects for datetime as is a doctrine entity
     * @return \Datetime
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }
    /**
     * using objects for datetime as is a doctrine entity
     * @param type $createdAt
     * @return \ZfModule\Entity\Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt;
        
        return $this;
    }
    /**
     * set an entity
     * @param \ZfModule\Entity\Comment $parent
     * @return \ZfModule\Entity\Comment
     */
    public function setParent($parent)
    {
        $this->parent=$parent;
        
        return $this;
    }
    /**
     * binary 
     * @return int
     */
    public function getHasParent()
    {
        return $this->hasParent;
    }
    /**
     * 1 or 0 
     * @param int $hasParent
     * @return \ZfModule\Entity\Comment
     */
    public function setHasParent($hasParent)
    {
        $this->hasParent = $hasParent;
        
        return $this;
    }    
}

?>
