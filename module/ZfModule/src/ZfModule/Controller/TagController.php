<?php

namespace ZfModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

/**
 * Description of CommentController
 *@todo validate input, throw and catch exceptions, test
 * @author gerard
 */
class TagController extends AbstractActionController
{
    /**
     * add comment, user must be logged in
     * @todo remove hardcoded userId
     * @return mixed view model or redirect
     * @throws \ZfModule\Controller\Exception\DomainException
     */
    public function addAction()
    {
                
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
          //  return $this->redirect()->toRoute('zfcuser/login');
        }
        list($userId, $moduleId, $tag) =  $this->requestInfo();           
             
        $service = new \ZfModule\Service\Tag();
        $service->setServiceLocator($this->getServiceLocator());
          
        $form = new \ZfModule\Form\Tag();
        $form->setData(
            array(
            /** @todo add module validator to chain */
            'module-id' => $moduleId,
            'tag'=> $tag,    
            )
        );
           
        if (!$form->isValid()) {
             
            return $this->renderValidationFailed($form);
        }           
            
        $success = $service->addNew($moduleId, $tag);
       
        $template = 'zf-module/tag/tag-wrapper.phtml';
        $vM = new ViewModel(
                array( 'module' => $success[0])
              );
        $vM->setTemplate($template);
        $vM->setTerminal(true); 
        
        return $vM;
    }

    /**
     * gets info frm params and auth 
     * return array($userId, $moduleId, $comment, $title);
     * @return array
     */
    public function requestInfo()
    {
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $moduleId = $this->params()->fromPost('module-id', '');
        $tag = $this->params()->fromPost('tag', ''); 
        $tagId = $this->params()->fromPost('tag-id', ''); 
        
        return array($userId, $moduleId, $tag, $tagId);
    }
   /**
    * edit module tag, user must be logged in
    * @return mixed either a view or redirect
    * @throws \ZfModule\Controller\Exception\DomainException
    * @todo consider changing this logic at some 
    * @todo point i'm going to need to write something 
    * @todo that doesn't include $moduleId 
    * @todo write tag form so valiadtion of none 
    * @todo required elements can be done here
    */
    public function editAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $moduleId, $tag, $tagId) = $this->requestInfo();
        $form = new \ZfModule\Form\Tag();
        $form->setData(
            array(
            /** @todo add module validator to chain */
            'module-id' => $moduleId,
            'tag'=> $tag,
            'tag-id'=>$tagId,
            )
        );
           
        if (!$form->isValid()) {
             
            return $this->renderValidationFailed($form);
        }          
        $service = $this->getServiceLocator()->get('zfmodule_tag_service');
       
        $success = $service->add($userId, $moduleId, $tagId, $tag);
        if ($success) {
            
            return $this->renderTag($success);
        }
        
        throw new Exception\DomainException('Something went wrong there');   
    }   
    /**
     * remove comment, user must be logged in
     * @return mixed either a view or redirect
     * @todo vailadte form
     * @throws \ZfModule\Controller\Exception\DomainException
     */
    public function removeAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $moduleId, $tag) = $this->requestInfo();
        $form = new \ZfModule\Form\Tag();
        $form->setData(
            array(
            /** @todo add module validator to chain */
            'module-id' => $moduleId,
            'tag-id'=>$tagId,
            )
        );
        if (!$form->isValid()) {
             
            return $this->renderValidationFailed($form);
        }  
        $service = $this->getServiceLocator()->get('zfmodule_tag_service');
       
        $success = $service->remove($userId, $moduleId, $tag);
        if ($success) {
           
            return $this->renderRemoveSuccess(); 
        }
        
        throw new Exception\DomainException('Something went wrong there');
    }
    /**
     * send remove success response
     * @return Zend\View\Model\ViewModel
     * @todo render a view 
     */
    public function renderRemoveSuccess()
    {
       
    }
    /**
     * render a single tag for frontend display
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel
     * @todo step through this
     */
    public function renderTag($result, $terminal=true)
    {
        $viewParams = array('tag' => $result);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_tag');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
     /**
     * set form on view model
     * @param Zend\Form\Form $form
     * @param boll $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderValidationFailed($form, $terminal=true)
    {
        $viewParams = array('replacementForm' => $form);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_tag_form');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
  
}
