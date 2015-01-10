<?php

namespace ZfModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of SearchIndexerController
 *
 * @author gerard
 */
class SearchIndexerController extends AbstractActionController
{
    public function allAction()
    {
        $this->indexModules();
    }
    protected function indexModules()
    {
        $service = new \ZfModule\Service\Search\ModuleIndexer();
        $options = new \ZfModule\Options\ModuleOptions;
        $service->setOptions($options);
        $service->setServiceLocator($this->getServiceLocator());
        $service->addAll();
    }
}
