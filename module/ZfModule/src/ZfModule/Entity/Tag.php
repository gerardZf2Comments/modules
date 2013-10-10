<?php

namespace ZfModule\Entity;
/**
 * tag entity class
 */
class Tag
{
    /**
    * tag id
    * @var int
    */
    protected $tagId = null;   
    /**
     * tag value
     * @var string
     */
    protected $tag = null;
    /**
     * Get id.
     * @return int
     */
    public function getTagId()
    {
        return $this->tagId;
    }
    /**
     * Set id.
     * @param int $id
     * @return \ZfModule\Entity\Tag
     */
    public function setTagId($id)
    {
        $this->tagId = (int) $id;
       
        return $this;
    }
    /**
     * Get Url
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
    /**
     * Set Url
     * @param string $tag
     * @return \ZfModule\Entity\Tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        
        return $this;
    }  
}
