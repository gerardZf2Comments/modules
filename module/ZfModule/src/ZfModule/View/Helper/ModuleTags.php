<?php

namespace ZfModule\View\Helper;


use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of Comments
 *@todo refactor methods
 * @author gerard
 */

class ModuleTags extends AbstractHelper implements ServiceLocatorAwareInterface 
{
    protected $tagContainer;
    protected $formContainer;
    protected $tagWrapper;
    /**
     * $var string template used for view
     */
    protected $viewTemplate;

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * 
     * @param Zend\view\Model\ViewModel $tagWrapper
     * @param Zend\view\Model\ViewModel $tagContainer
     * @param Zend\view\Model\ViewModel $formContainer
     */
    public function __construct($tagWrapper, $tagContainer, $formContainer) 
    {
        $this->setFormContainer($formContainer);
        $this->setTagContainer($tagContainer);
        $this->setTagWrapper($tagWrapper);
    }

    /**
     * 
     * @param int $moduleId
     * @param int $limit 
     * @param string $order
     * @param string $sort
     */
    public function __invoke($tags, $moduleId, $form = false)
    {        
      
        $tagWrapper = $this->getTagWrapper();
        $formContainer = $this->getFormContainer();
        $tagContainer = $this->getTagContainer();
        
        if($form){
            $formContainer->setVariable('replacementForm', $form);
        }
        $tagContainer->setVariable('tags', $tags);
        
        $tagWrapper->setVariable('moduleId', $moduleId);
        $tagWrapper->addChild($formContainer, 'formContainer');
        $tagWrapper->addChild($tagContainer, 'tagContainer');
       
        return $this->getView()->render($tagWrapper);
        
    }
    public function getTagWrapper()
    {
        return $this->tagWrapper;
    }
    public function setTagWrapper($tagWrapper)
    {
        $this->tagWrapper = $tagWrapper;
       
        return $this;
    }
    public function getFormContainer()
    {
        return $this->formContainer;
    }
    public function setFormContainer($formContainer)
    {
        $this->formContainer = $formContainer;
       
        return $this;
    }
    public function getTagContainer()
    {
        return $this->tagContainer;
    }
    public function setTagContainer($tagContainer)
    {
        $this->tagContainer = $tagContainer;
       
        return $this;
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


