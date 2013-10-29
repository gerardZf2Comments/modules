<?php

namespace ZfModule\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * @author gerard
 */
class TagDisplay  extends AbstractHelper implements ServiceLocatorAwareInterface
{
    public function __invoke($entity)
    {
        $tags = $entity->getTags();
        $tagView = new ViewModel(
            array(
                'tags' => $tags,
             )
        );
        
        $template = 'zf-module/tag/tag-container.phtml';
        $tagView->setTemplate($template);
        
        return $this->getView()->render($tagView);
    }
  /**
     * {@inheritdoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
