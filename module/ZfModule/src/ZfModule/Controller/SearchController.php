<?php

namespace ZfModule\Controller;

use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// use ZfModule\View\Model\Exception;


/**
 * Description of CommentController
 *@todo validate input, throw and catch exceptions, test
 * @author gerard
 */
class SearchController extends AbstractActionController
{
     /**
     * 
     *ajax rendering of tag search
     * @todo limit search
     */
    public function tagAjaxAction()
    {
          $s =$this->getServiceLocator()->get('zfmodule_service_search_module_indexer');
        $s->setServiceLocator($this->getServiceLocator());
    $s->addAll();
          //do search
        $query =  $this->params()->fromRoute('query', '');
        $tags = $this->getTags($query);
          
        return $this->renderTagLayout($tags, true);       
    }
    /**
     * 
     * tag search
     * @todo limit search
     */
    public function tagAction()
    {
        
        $query =  $this->params()->fromRoute('query', '');
        $tags = $this->getTags($query);
        
        return $this->renderTagLayout($tags);       
    }
    /**
     * search module indexs render for ajax or full layout
     * @return \Zend\View\Model\ViewModel
     */
    public function moduleAjaxAction()
    {  
        $this->getEventManager()->trigger('insert.post', $this);
      //  $em->trigger('someExpensiveCall.pre', $this);
        $this->index();
         //do search
        $query =  $this->params()->fromRoute('query', '');
        $modules = $this->getModules($query); 
        $modules = ($modules) ? $modules : array();
         //pagination
        $paginator = $this->getPaginator($modules);
        $currentModules = $paginator->getCurrentItems();
        
        return $this->renderModuleLayout($currentModules, $paginator, $query, true);
    }
    /**
     * 
     * module search
     */
    public function moduleAction()
    { 
       
         //do search
        $query =  $this->params()->fromRoute('query', '');
        $modules = $this->getModules($query);
         //pagination
        $paginator = $this->getPaginator($modules);
        $currentModules = $paginator->getCurrentItems();
        
        return $this->renderModuleLayout($currentModules, $paginator, $query);
    }
    /**
     * instanciate a paginator with array addaper
     * set the page and limit
     * @param array $modules
     * @return \Zend\Paginator\Paginator
     * @todo replace wih service factory
     */
    public function getPaginator(array $modules) 
    {
        $currentPage = (int)$this->params()->fromRoute('page', 1);
        $itemCountPerPage = (int) $this->params()->fromRoute('limit', 15);
        $adapter = new \Zend\Paginator\Adapter\ArrayAdapter($modules);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage($itemCountPerPage);
        return $paginator;
    }

    /**
     * adds modules to layout object and sets terminal if (ajax)
     * @param \ArrayIterator $modules
     * @param \Zend\Paginator\Paginator  $paginator 
     * @param string $query
     * @param bool $ajax
     * @return \Zend\View\Model\ViewModel
     */
    public function renderModuleLayout(\ArrayIterator $modules, \Zend\Paginator\Paginator $paginator, $query, $ajax = false)
    {      
        $viewModel = new ViewModel(array(
            'modules' => $modules,
            'paginator' => $paginator,
            'query' => $query,
        ));
        $viewModel->setTerminal($ajax);
        
        return $viewModel;
    }
     /**
     * adds tags to layout object and sets terminal if (ajax)
     * @param array $tags
     * @param bool $ajax
     * @return \Zend\View\Model\ViewModel
     */
    public function renderTagLayout(array $tags, $ajax = false)
    {        
        $viewModel = new ViewModel(array(
            'tags' => $tags,
        ));
        $viewModel->setTerminal($ajax);
        
        return $viewModel;
    }
    /**
     * 
     * @param string $query
     * @return array
     */
    public function getModules($query)
    {
        $service = $this->getServiceLocator()->get('zfmodule_service_search_module_search');
      
        $service->setServiceLocator($this->getServiceLocator());
          
        return $service->search($query); 
    }
    /**
     * 
     * @param string $query
     * @return array
     * @todo write service facory for  new \ZfModule\Service\TagSearch();
     */
     public function getTags($query)
    {
        $service = new \ZfModule\Service\TagSearch();
        $service->setServiceLocator($this->getServiceLocator());
          
        return $service->search($query); 
    }

    
    
    
    
    
    
    
    
    
    
    
    
    public function index()
    {
        $service = new \ZfModule\Service\Search\ModuleIndexer();
        $options = new \ZfModule\Options\ModuleOptions;
        $service->setOptions($options);
        $service->setServiceLocator($this->getServiceLocator());
        $service->addAll();
    }
    
}