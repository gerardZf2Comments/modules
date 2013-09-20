<?php

namespace ZfModule\Entity;

class Tag // implements ModuleInterface
{
     /**
     * @var id
     */
    protected $tagId = null;
   
    /**
     * @var tag
     */
    protected $tag = null;

    /**
     * Get id.
     *
     * @return int
     */
    public function getTagId()
    {
        return $this->tagId;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return ModuleInterface
     */
    public function setTagId($id)
    {
        $this->tagId = (int) $id;
        return $this;
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Url
     *
     * @param string $url
     * @return ModuleInterface
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    
   
}
