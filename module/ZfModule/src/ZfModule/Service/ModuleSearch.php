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

    /**
     * from the mdule options
     * @return string
     */
     public function getIndexPath()
     {
        $options = new \ZfModule\Options\ModuleOptions;
         return $options->getModuleIndexPath();
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
    public function search($query, $sort = null, $sortType = null, $sortOrder = null) 
    {
        $index = $this->initIndex();
        if ($sort) {
            $sortOrder = ($sortOrder === null) ? $this->sortOrder : $sortOrder;
            $sortType = ($sortType === null) ? $this->sortType : $sortType;           
            
            return $index->find($query, $sort, $sortType, $sortOrder);
        } else {
           
            return $index->find($query);            
        }
    }
    /**
     * @return  \ZendSearch\Lucene\Index
     */
    public function initIndex() {

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
