<?php

namespace ZfModule\Service\Search;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;
use ZendSearch\Lucene\Index;
/**
 * does a cached search
 *@todo refactor location and test
 * @author gerard
 */
class ModuleSearch extends EventProvider implements ServiceLocatorAwareInterface 
{
    /**
     * index
     * @var ZendSearch\Lucene\Index
     */
    protected $index;
    /**
     * sortType = SORT_NUMERIC
     * @var int
     */
    protected $sortType = SORT_NUMERIC;
    /**
     * sortOrder = SORT_DESC
     * @var int
     */
    protected $sortOrder = SORT_DESC; 
    /**
     * options 
     * @var ZfModule\Options\ModuleOptions
     */
    protected $options;
    /**
     * options
     * @param \ZfModule\Options\ModuleOptions $options
     * @return \ZfModule\Service\Search\ModuleSearch
     */
    public function setOptions( \ZfModule\Options\ModuleOptions $options)
    {
        $this->options = $options;
        
        return $this;
    }
    /**
     * options
     * @return ZfModule\Options\ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
    /**
     * from the mdule options
     * @return string
     */
    public function getIndexPath()
    {        
        return $this->getOptions()->getModuleIndexPath();
    }   
     /**
     * private search
     * @param string $query
     * @param int|string $sort
     * @param int $sortType
     * @param int $sortOrder
     * @return array|\ZendSearch\Lucene\Search\QueryHit
     * @throws \ZendSearch\Lucene\Exception\InvalidArgumentException
     * @throws \ZendSearch\Lucene\Exception\RuntimeException
     */
    private function _search($query) 
    {
        $index = $this->initIndex();

        $results = $index->find($query);            
        
        return $results;
    }
    /**
     * do lucene search and then use mapper to get full results
     * @param string $query
     * @param bool sort sort the query by watched 
     * @return array
     */
    public function search($query, $sort = true)
    {
        $entities = $this->getCachedSearch($query);
        
        if(!$entities){
            $searchResults = $this->_search($query);
            $entityResults = $this->fullArraysFromResults($searchResults, $sort);
            if($sort){
                $sort = "sort";
                $query .= $query.$sort;
            }
            $this->cacheResults($entityResults, $query);
        }    

        return $entityResults;       
    }
    /**
     * takes results from lucene and uses mapper to create
     * arrays not entitys
     * @param array $results
     * @param bool $sort
     * @return array
     */
    private function fullArraysFromResults($results,  $sort =true)
    {
        $inArray = array();
        foreach($results as $queryHit){
             /** @var $queryHit ZendSearch\Lucene\Search\QueryHit */
            $inArray[] = $queryHit->moduleId;
        }
        
        $moduleMapper = $this->getServiceLocator()->get('zfmodule_mapper_module');       
       
        return $moduleMapper->findByInAsArray($inArray, $sort);
    }
    /**
     * results can be retrieved by searh query
     * @param string $query
     * @return array||null
     */
    public function getCachedSearch($query)
    {
        $cache = $this->getCache();    
           
        return  $cache->get($query);
    }
    /**
     * cache object for module search
     * @return \ZfModule\Service\Search\ModuleCache
     */
    public function getCache()
    {
        return $this->getServiceLocator()->get('zfmodule_service_search_module_cache');
    }
    /**
     * sets results in cache
     * @param array||null $cachable
     * @param string $query 
     *  @return \ZfModule\Service\Search\ModuleSearch
     */
    public function cacheResults($cachable, $query)
    {
        if ($cachable){
            $this->getCache()->set($cachable, $query);      
        }
       
        return $this;
    }
    /**
     * gets index using path 
     * @return  \ZendSearch\Lucene\Index
     */
    public function initIndex() 
    {
        /** @var index \ZendSearch\Lucene\Index  */
        return $this->getIndex($this->getIndexPath());        
    }
    /**
     * set index
     * @param \ZendSearch\Lucene\Index $index
     * @return \ZfModule\Service\ModuleIndexer
     */
    public function setIndex( Index $index)
    {
        $this->index = $index;
        
        return $this;
    }
    /**
     * index
     * @param string $path
     * @param bool $create
     * @return mixed ZendSearch\Lucene\Index || null
     */
    public function getIndex( $path = null)
    {
        if( !$this->index && $path ){
            $this->index = new Index( $path );
        }
        
        return $this->index;
    }
   /**
    * get service locator
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }
    /**
     * set service locator
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     *  @return \ZfModule\Service\Search\ModuleSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm) 
    {
        $this->serviceLocator = $sm;
       
        return $this;
    }
    /**
     * clears cache
     * @todo refactor logic to chache object
     */
    public function clearCache()
    {
        /* @var $cache StorageInterface */
        $cache = $this->getServiceLocator()->get('zfmodule_cache');
        $cache->clearByTags($this->getCacheTags());
    }
    /**
     * array(md5($this->getIndexPath()));
     * @return array
     * @todo refactor logic to chache object
     */
    public function getCacheTags()
    {
        return array(md5($this->getIndexPath()));
    }
}

