

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;
use ZendSearch\Lucene\Index;
/**
 * Description of ModuleSearch
 *@todo refactor location and test
 * @author gerard
 */
class ModuleSearch extends EventProvider implements ServiceLocatorAwareInterface 
{

    /**
     *
     * @var ZendSearch\Lucene\Index
     */
    protected $index;
    /**
     *
     * @var int
     */
    protected $sortType = SORT_NUMERIC;
    /**
     *
     * @var int
     */
    protected $sortOrder = SORT_DESC; 
    /**
     * @var ZfModule\Options\ModuleOptions
     */
    protected $options;
    
    /**
     * 
     * @param \ZfModule\Options\ModuleOptions $options
     * @return \ZfModule\Service\Search\ModuleSearch
     */
    public function setOptions( \ZfModule\Options\ModuleOptions $options)
    {
        $this->options = $options;
        
        return $this;
    }
    /**
     * 
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
    /**
     * do lucene search and then use mapper to get full results
     * @param string $query
     * @return array
     */
    public function search($query, $sortByWatch)
    {
        $entities = $this->getCachedSearch($query);
        if(!$entities){
            $searchResults = $this->_search($query);
            $entityResults = $this->fullArraysFromResults($searchResults);
            $this->cacheResults($entityResults, $query);
        }    

        return $entities;       
    }
    /**
     * takes results from lucene and uses mapper to create
     * arrays not entitys
     * @param array $results
     * @param bool $name we only sort by watched or search engine rating
     * @return array
     */
    private function fullArraysFromResults($results, $sort)
    {
        $inArray = array();
        foreach($results as $queryHit){
             /** @var $queryHit ZendSearch\Lucene\Search\QueryHit */
            $inArray[] = $queryHit->moduleId;
        }
        
        $moduleMapper = $this->getServiceLocator()->get('zfmodule_mapper_module');
        $sort=true;
        return $moduleMapper->findByInAsArray($inArray, $sort);
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
     * @return \ZfModule\Service\Search\ModuleSearch
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
     *  @return \ZfModule\Service\Search\ModuleSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm) 
    {
        $this->serviceLocator = $sm;
       
        return $this;
    }

}

?>
