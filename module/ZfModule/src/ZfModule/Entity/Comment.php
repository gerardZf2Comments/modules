<?php

namespace ZfModule\Entity;
use Comments\Entity\Comment as superComment;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ModuleComment
 *
 * @author gerard
 */
class Comment extends superComment
{
    /**
     *collection of \ZfModule\Entity\Comment
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    protected $children;
    
    
    protected $module;
    
    /**
     * $this->children = new ArrayCollection;
     * @return \ZfModule\Entity\Comment
     */
    public function __construct() {
        $this->replies = new ArrayCollection;
        return $this;
    }
   
  
    /**
     * get module id
     * @return int
     */
    public function getModule (){
       return $this->module;
    }
    /**
     * set module id
     * @param int $moduleId
     * @return \ZfModule\Entity\Comment
     */
    public function setModule ($module){
        $this->module = $module;
        return $this;
    }
}