<?php

namespace ZfModule\Service\Search;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcBase\EventManager\EventProvider;
use ZendSearch\Lucene\Index;

/**
 * 
 *
 * @author gerard
 */
class ModuleCache 
{
    protected $options =null;
    protected $cache = null;
    protected $serviceLocator=null;
    public function __construct($cache, $options, $sm) 
    {
        $this->setCache($cache);
        $this->setOptions($options);
        $this->setServiceLocator($sm);
    }
    public function getServiceLocator()
    {
        return $this->serviceLocator; 
    }
    public function setServiceLocator($sm)
    {
        $this->serviceLocator=$sm;
        
        return $this;
    }

    public function setOptions($options)
    {
        $this->options= $options;
        
        return $this;
    }
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * array(md5($this->getIndexPath()));
     * @return array
     */
    public function getCacheTags($query = null)
    {
        $tags = array(md5($this->getIndexPath()));
        if($query){
        $queryTag = $this->getQueryTag($query);
        
        $tags[] = $queryTag;
        }
        
        return $tags;
    }
    public function getQueryTag($query)
    {
        $query = strtolower($query);
        $query = trim($query);
        return md5($query);
    }

    public function clearCachedQuery($query)
    {
        $queryTag = array($this->getQueryTag($query));
        $this->getCache()->clearByTags($queryTag); 
    }

    public function clearCache()
    {
        /* @var $cache StorageInterface */
        $cache = $this->getServiceLocator()->get('zfmodule_cache');
        $cache->clearByTags($this->getCacheTags());
    }
    public function get($query) 
    {
        /* @var $cache StorageInterface */
        $cache = $this->getServiceLocator()->get('zfmodule_cache');
        $cacheKey = $this->getCacheKey($query);

        return $cache->getItem($cacheKey);
    }
    public function getCacheKey($query)
    {
        return md5($this->getIndexPath() . $query);
    }
     /**
     * from the mdule options
     * @return string
     */
     public function getIndexPath()
     {        
         return $this->getOptions()->getModuleIndexPath();
     } 

    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }
     public function getCache()
    {
         return $this->cache;
      
    }
   
    public function set($cachable, $query)
    {
        $cache = $this->getCache();
        $cacheKey = $this->getCacheKey($query);
        $cache->setItem($cacheKey, $cachable);
        $cache->setTags($cacheKey,  $this->getCacheTags($query));
    }
    

}