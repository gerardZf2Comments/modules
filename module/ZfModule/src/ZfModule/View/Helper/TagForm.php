<?php

namespace ZfModule\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * @author gerard
 */
class TagForm  extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $_form = null;
    
    public function __invoke($tag  = null )
    {   
        if ($this->userNotAllowed()) {
            
            return $this;
        }
        
        if ($tag) {
            $this->addFormData($this->getForm(), $tag);
        }
        
        $vM = new ViewModel(array(
            'form' => $this->getForm(),
        ));
        
        $t = "zf-module/tag/form-container";
        $vM->setTemplate($t);
        
        return $this->getView()->render($vM);
    }
    /**
     * @todo run a check
     * @return boolean
     */
    public function userNotAllowed()
    {
        return false;
    }

    public function setForm($form)
    {
        $this->_form = $form;
        
        return $this;
    }
    public function getForm()
    {
        return $this->_form;
    }

    public function addFormData($form, $tag)
    {
        $data = array(
            'tag-id' => $tag->getTagId(),
            'tag' => $tag->getTag(),
        );
        $form->setData();
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
