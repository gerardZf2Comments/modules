<?php

namespace User\Entity;

use ZfcUserDoctrineORM\Entity\User as ZfcUser;

class User extends ZfcUser
{
    public function __construct($initializer = null, $cloner = null) {
        $this->userComments = array();
        $this->state = 1;
    }

    protected $userComments;
    /**
     * @var string
     */
    protected $photoUrl;

    protected $createdAt;
    public function setState($state)
    {
        $this->state = $state;
        
        return $this;
    }
    public function getState()
    {
        return $this->state;
    }

    public function getUserComments()
    {
        return $this->userComments;
    }
    public function setUserComments($uc)
    {
        $this->userComments= $uc;
        return $this;
        
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    /**
     * get Photo Url
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * set Photo Url
     * @param string $photo
     * @return UserInterface
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
        return $this;
    }
}
