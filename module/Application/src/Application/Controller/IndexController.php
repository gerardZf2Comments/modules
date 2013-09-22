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
use Zend\Feed\Writer\Feed;
use Zend\View\Model\FeedModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $query =  $this->params()->fromQuery('query','cunt');

         $em = $this->getServiceLocator()->get('zfcuser_user_mapper');
     $users = $em->findById(2);
        $page = (int) $this->params()->fromRoute('page', 1);
        $modules= $this->getModules($query);
        $adapter = new \Zend\Paginator\Adapter\ArrayAdapter($modules);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(15);
        /*
        $sm = $this->getServiceLocator();
        $mapper = $this->getServiceLocator()->get('zfmodule_mapper_module');

        $repositories = $mapper->pagination($page, 15, $query, 'createdAt', 'DESC');
        $repositories = $this->getServiceLocator()->get('zfmodule_service_module');
         */
        return array(
            'repositories' => $paginator,
            'query' => $query,
        );
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
     * RSS feed for recently added modules
     * @return FeedModel
     */
    public function feedAction()
    {
        // Prepare the feed
        $feed = new Feed();
        $feed->setTitle('ZF2 Modules');
        $feed->setDescription('Recently added modules.');
        $feed->setFeedLink('http://modules.zendframework.com/feed', 'atom');
        $feed->setLink('http://modules.zendframework.com');

        // Get the recent modules
        $page = 1;
        $mapper = $this->getServiceLocator()->get('zfmodule_mapper_module');
        $repositories = $mapper->pagination($page, 15, null, 'created_at', 'DESC');

        // Load them into the feed
        foreach ($repositories as $module) {
            $entry = $feed->createEntry();
            $entry->setTitle($module->getName());

            if($module->getDescription() == '') {
                $moduleDescription = "No Description available";
            } else {
                $moduleDescription = $module->getDescription();
            }

            $entry->setDescription($moduleDescription);
            $entry->setLink($module->getUrl());
            $entry->setDateCreated(strtotime($module->getCreatedAt()));

            $feed->addEntry($entry);
        }

        // Render the feed
        $feedmodel = new FeedModel();
        $feedmodel->setFeed($feed);

        return $feedmodel;
    }

}
