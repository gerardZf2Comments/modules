<?php

namespace ZfModule\Entity;

use ArrayObject;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * module entity
 */
class Module implements ModuleInterface
{
    /**
     * the repoid as defined by web service
     * @var int
     * @todo see if this is really needed or integrated
     */
    protected $repoId;
    /**
     * the watched count from webservice
     * @var int
     */
    protected $watched;
    /**
     *collection or proxy of ZfModule\Comment\Entity
     * @var ArrayObject
     */
    protected $comments;
    /**
     * collection or proxy ZfModule\Entity\tag
     * @var ArrayObject
     */
    protected $tags;
    /**
     * internal module id
     * @var int
     */
    protected $id = null;
   

    /**
     * module name as defined by service
     * @var string
     */
    protected $name = null;

    /**
     *  defined by service
     * @var string
     */
    protected $description = null;

    /**
     *  github url defined by service
     * @var string
     */
    protected $url = null;

    /**
     * using objects here
     * @var \Datetime
     */
    protected $createdAt;

    /**
     * using objects here
     * @var \Datetime
     */
    protected $updatedAt;

    /**
     * as defined by service $data->owner->login
     * @var string
     */
    protected $owner = null;

    /**
     * as defined by service $data->owner->avatar
     * @var string
     */
    protected $photoUrl = null;
    /**
     * don't know why this is here the db colum is empty and no setters or getters
     * @var mixed
     */
    protected $metaData;
    /**
     *  $this->tags = new ArrayCollection;
     *   $this->comments = new ArrayCollection;
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection;
        $this->comments = new ArrayCollection;
        $this->createdAt = new \DateTime('now'); 
    }
    /**
     * collection
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }
    /**
     * the git watched count
     * @return int 
     */
    public function getWatched()
    {
        return $this->watched;
    }
    /**
    * the git watched count
    * @param int $watched
    * @return \ZfModule\Entity\Module
    */
    public function setWatched($watched)
    {
        $this->watched = $watched;
        
        return $this;
    }
    /**
     * as defined by service $data->owner->avatar
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }
    /**
     * as defined by service $data->owner->avatar
     * @param string $photoUrl
     * @return \ZfModule\Entity\Module
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
        return $this;
    }
    /**
    * as defined by service $data->owner->login
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * as defined by service $data->owner->avatar
     * @param string $owner
     * @return \ZfModule\Entity\Module
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }
    /**
     * defaults at db layer
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * defaults at db layer
     * @param \Datetime $updatedAt
     * @return \ZfModule\Entity\Module
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    /**
     * the github defined repo id
     * @return int
     */
    public function getRepoId(){
        return $this->repoId;
    }
    /**
    * the github defined repo id
    * @param int $repoId
    * @return \ZfModule\Entity\Module
    */
    public function setRepoId($repoId)
    {
        $this->repoId = $repoId;
        
        return $this;
    }
    /**
     * datetime
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * set datetime
     * @param \Datetime $createdAt
     * @return \ZfModule\Entity\Module
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set id.
     * @param int $id
     * @return ModuleInterface
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        
        return $this;
    }
    /**
     * Get Url
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Set Url
     * @param string $url
     * @return ModuleInterface
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    /**
     * Get Description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set Description
     * @param string $description
     * @return ModuleInterface
     */
    public function setDescription($description)
    {
        $this->description = $description;
       
        return $this;
    }
    /**
     * Get Name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set Name
     * @param string $name
     * @return ModuleInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    /**
     * collection
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;        
    }
}
