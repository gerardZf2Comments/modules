<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;
use ZendSearch\Lucene\Index;
/**
 * Description of ModuleSearch
 *
 * @author gerard
 */
class ModuleSearch extends EventProvider implements ServiceLocatorAwareInterface 
{

    protected $index;
    protected $sortType = SORT_NUMERIC;
    protected $sortOrder = SORT_DESC;
    //works frm base of vendor or module directory of a 
    //normal sample app
    protected $indexPath;
    protected $options;
    
    public function setOptions($options){
        $this->options = $options;
    }
     public function getOptions(){
          return new \ZfModule\Options\ModuleOptions;
        $this->options;
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
     * 
     * @param string $query
     * @param int|string $sort
     * @param int $sortType
     * @param int $sortOrder
     * @return array|\ZendSearch\Lucene\Search\QueryHit
     * @throws \ZendSearch\Lucene\Exception\InvalidArgumentException
     * @throws \ZendSearch\Lucene\Exception\RuntimeException
     */
     private function _search($query, $sort = null, $sortType = null, $sortOrder = null) 
    {
        $index = $this->initIndex();
        if ($sort) {
            $sortOrder = ($sortOrder === null) ? $this->sortOrder : $sortOrder;
            $sortType = ($sortType === null) ? $this->sortType : $sortType;           
            
            $results = $index->find($query);
        } else {
           
            $results = $index->find($query);            
        }      
        
        return $results;
    }
    public function search($query){
        $entities = $this->getCachedSearch($query);
        if(!$entities){
            $searchResults = $this->_search($query);
            $entityResults = $this->entitiesFromResults($searchResults);
            $this->cacheResults($entityResults, $query);
        }
        
        return $entities;       
    }

    private function entitiesFromResults($results)
    {
        $inArray = array();
        foreach($results as $queryHit){
            /** @var $queryHit ZendSearch\Lucene\Search\QueryHit */
            $inArray[] = $queryHit->moduleId;
        }
        
        $moduleMapper = $this->getServiceLocator()->get('zfmodule_mapper_module');
        
        return $moduleMapper->findByInAsArray($inArray);
    }

    /**
     * 
     * @param string $query
     * @return array||null
     */
    public function getCachedSearch($query)
    {
        /* @var $cache StorageInterface */
        $cache = $this->getServiceLocator()->get('zfmodule_cache');
        $cacheKey = md5($this->getIndexPath().$query);        
        
            return $cache->getItem($cacheKey);
    }
    /**
     * 
     * @param array||null $cachable
     * @param string $query 
     * @return \ZfModule\Service\ModuleSearch
     */
    public function cacheResults($cachable, $query)
    {
        if ($cachable){
        /* @var $cache StorageInterface */
        $cache = $this->getServiceLocator()->get('zfmodule_cache');
        $cacheKey = md5($this->getIndexPath().$query);
        $cache->setItem($cacheKey, $cachable);
        }
        return $this;
    }

    /**
     * @return  \ZendSearch\Lucene\Index
     */
    public function initIndex() 
    {
        /** @var index \ZendSearch\Lucene\Index  */
        return $this->getIndex($this->getIndexPath());        
    }
    /**
     * 
     * @param \ZendSearch\Lucene\Index $index
     * @return \ZfModule\Service\ModuleIndexer
     */
    public function setIndex( Index $index)
    {
         $this->index = $index;
         return $this;
    }
    /**
     * 
     * @param string $path
     * @param bool $create
     * @return ZendSearch\Lucene\Index
     */
    public function getIndex( $path = null)
    {
        if( !$this->index && $path ){
            $this->index = new Index( $path );
        }
        return $this->index;
    }
   /**
    * 
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\ModuleSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm) 
    {
        $this->serviceLocator = $sm;
        return $this;
    }

}

?>
