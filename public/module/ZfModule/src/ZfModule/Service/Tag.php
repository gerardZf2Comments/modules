<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;

/**
 * Description of TagIndexer
 *
 * @author gerard
 * @todo find 2 level cache with same methods as files \Zend\Cache\Storage\Adapter\Filesystem
 * @todo find view/model caching module
 * @todo refactor location and test
 */
class Tag extends EventProvider implements ServiceLocatorAwareInterface
{
    /**
     * service locator
     * @var \Zend\ServiceManager\ServiceLocator
     */
    protected $serviceLocator;
    
    /**
     * 
     * @param string $query
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function search($query)
    {
        $tagMapper = $this->getServiceLocator()->get('zfmodule_mapper_tag');      
     
        return  $tagMapper->findByStarts($query, 15);                    
    } 
    public function addNew($moduleId, $tag)
    {
        $mM = $this->getServiceLocator()->get('zfmodule_mapper_module');
        $module = $mM->findById($moduleId);
        $tM = $this->getServiceLocator()->get('zfmodule_mapper_tag');
        $tag = $tM->findOrCreate($tag);
        $tags = $module->getTags();
             
        if ( ! $tags->contains($tag)) {
            $tags->add($tag);
        }
        $mM->update($module);
        
        return array($module, $tag);
    }
        
    /**
     *  Zend\ServiceManager\ServiceLocator
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }
    
    /**
     *  Zend\ServiceManager\ServiceLocator
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\TagSearch
     */
    public function setServiceLocator(ServiceLocatorInterface $sm)
    {
        $this->serviceLocator = $sm;
        return $this;
    }
}
