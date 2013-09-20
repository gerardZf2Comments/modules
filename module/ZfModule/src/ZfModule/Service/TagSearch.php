<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;

/**
/**
 * Description of TagIndexer
 *
 * @author gerard
 * @todo find 2 level cache with same methods as files \Zend\Cache\Storage\Adapter\Filesystem
 * @todo find view/model caching module
 */
class TagSearch extends EventProvider implements ServiceLocatorAwareInterface {
    protected $serviceLocator;
    
    /**
     * 
     * @param string $query
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function search($query){
       $cache = new \Zend\Cache\Storage\Adapter\Filesystem();
       $cache->getOptions()->setTtl(3600);
         
       $plugin = new \Zend\Cache\Storage\Plugin\ExceptionHandler();
       $plugin->getOptions()->setThrowExceptions(false);
       $cache->addPlugin($plugin);
       $cache->getOptions()->setNamespace(__NAMESPACE__.__CLASS__);
       
       $sm = $this->getServiceLocator();
       $tagMapper = $sm->get('zfmodule_mapper_tag');
      
     
       return  $tagMapper->findByStarts($query, 15);
                     
           
    }
    
    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\TagSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm) {
        $this->serviceLocator = $sm;
        return $this;
    }

}

?>
