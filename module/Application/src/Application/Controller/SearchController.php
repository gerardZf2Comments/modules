<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    /**
     * 
     *ajax rendering of tag search
     * @todo limit search
     */
    public function tagAjaxAction()
    {
          $s = new \ZfModule\Service\ModuleIndexer;
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
     * @param array $modules
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator(array $modules) 
    {
        $currentPage = (int)$this->params()->fromQuery('page', 1);
        $itemCountPerPage = (int) $this->params()->fromQuery('limit', 15);
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
        $service = new \ZfModule\Service\ModuleSearch();
        $service->setServiceLocator($this->getServiceLocator());
          
        return $service->search($query); 
    }
    /**
     * 
     * @param string $query
     * @return array
     */
     public function getTags($query)
    {
        $service = new \ZfModule\Service\TagSearch();
        $service->setServiceLocator($this->getServiceLocator());
          
        return $service->search($query); 
    }

    
    
    
    
    
    
    
    
    
    
    
    
    public function index(){
        $service = new \ZfModule\Service\ModuleIndexer();
        $service->setServiceLocator($this->getServiceLocator());
        $service->addAll();
    }
    
}
