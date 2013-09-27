<?php

namespace ZfModule\Entity;
use Doctrine\Common\Collections\ArrayCollection;

class Module implements ModuleInterface
{
    protected $comments;
    /**
     * @var ZfModule\Entity\tag
     */
    protected $tags;
    /**
     * @var id
     */
    protected $id = null;
   

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @var datetime
     */
    protected $createdAt;

    /**
     * @var datetime
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $owner = null;

    /**
     * @var string
     */
    protected $photoUrl = null;

    protected $metaData;
    /**
     *  $this->tags = new ArrayCollection;
        $this->comments = new ArrayCollection;
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection;
        $this->comments = new ArrayCollection;
    }
    /**
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags(){
        return $this->tags;
    }
    /**
     * 
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }
    /**
     * 
     * @param string $photoUrl
     * @return \ZfModule\Entity\Module
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * 
     * @param string $owner
     * @return \ZfModule\Entity\Module
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }
    /**
     * 
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * 
     * @param \Datetime $updatedAt
     * @return \ZfModule\Entity\Module
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
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
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set Url
     *
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
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Description
     *
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
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return ModuleInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getComments(){
        return $this->comments;
        
    }
}
