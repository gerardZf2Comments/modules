<?php

namespace ZfModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;

/**
 * controller to search tags and modules
 *@todo validate input, throw and catch exceptions, test
 * @author gerard
 */
class SearchController extends AbstractActionController
{
    /**
     * tag search
     * @todo limit & order search
     * @return Zend\View\Model\ViewModel 
     */
    public function tagAjaxAction()
    {        
        $query =  $this->params()->fromRoute('query', '');
        $tags = $this->getTags($query);
        
        return $this->renderTagLayout($tags, true);       
    }
    /**
     * search module indexs render for ajax or full layout
     * @return \Zend\View\Model\ViewModel
     */
    public function moduleAjaxAction()
    {  
        $this->indexModules();
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
     * module search
     * @return Zend\View\Model Description
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
    public function renderModuleLayout(\ArrayIterator $modules, Paginator $paginator, $query, $ajax = false)
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
     * uses service to get array of modules
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
     * uses \ZfModule\Service\TagSearch to get a array of tags
     * @param string $query
     * @return array
     * @todo write service facory for  new \ZfModule\Service\Tag();
     */
    public function getTags($query)
    {
        $service = new \ZfModule\Service\Tag();
        $service->setServiceLocator($this->getServiceLocator());
          
        return $service->search($query); 
    }
    /**
     * this exists in searchIndexController
     * @deprecated since version 1
     */
    protected function indexModules()
    {
        $service = new \ZfModule\Service\Search\ModuleIndexer();
        $options = new \ZfModule\Options\ModuleOptions;
        $service->setOptions($options);
        $service->setServiceLocator($this->getServiceLocator());
        $service->addAll();
        
        return;
    }
}
