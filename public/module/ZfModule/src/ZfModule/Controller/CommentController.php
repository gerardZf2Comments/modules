<?php

namespace ZfModule\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

/**
 * controller handles the requests to add and render comments
 * @todo validate input, throw and catch exceptions, test
 * @todo make this clearly fully ajax
 * @todo fix all exceptions 
 * @todo remove hard coded user id
 * @author gerard
 */
class CommentController extends AbstractActionController
{
    /**
     * add comment, user must be logged in
     * @return view model
     * @throws ZfModule\Controller\Exception\DomainException
     * @todo remove hardcoded user id
     */
    public function addAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
          //  return $this->redirect()->toRoute('zfcuser/login');
        }
        list($user, $moduleId, $comment, $title) = $this->requestInfo();           
             
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
     
        $form = new \Comments\Form\Comment();
        $formIsValid = $this->commentsValidate()->validateCommentForm($form, $moduleId, $comment, $title);
          
        if (!$form->isValid()) {
              
           return $this->renderAddValidationFailed($form);
        } 
        //@todo remove this    
        $userId= 1;
        $success = $service->add($user, $moduleId, $comment, $title);         
       
        // it seems the add function either returns a collection or something else
        if ($success) {
            $success = $success;
            
            return $this->renderComment($success, true); 
        }
        
        $message = 'Comment not added';
        
        throw new Exception\DomainException($message);
        
    }
    

    /**
     * render view model with form printing messages
     * @param Zend\Form\Form $form
     * @param bool $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderAddReplyValidationFailed($form, $terminal=true)
    {
        $viewParams = array('replacementForm' => $form);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_comment_reply_form');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
    /**
     * render form with messages in view model
     * @param Zend\Form\Form $form
     * @param boll $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderAddValidationFailed($form, $terminal=true)
    {
        $viewParams = array('replacementForm' => $form);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_comment_form');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }

   /**
    * add reply 
    * @return mixed view or redirect
    * @throws Exception\DomainException
    * @todo remove hardcoded userId
    */
    public function addReplyAction()
    {    

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            //return $this->redirect()->toRoute('zfcuser/login');
        }
        list($user, $parentCommentId, $comment) = $this->getReplyFormParams();
        
        $form = new \ZfModule\Form\CommentReply();
        $formIsValid = $this->commentsValidate()->validateReplyForm($form, $parentCommentId, $comment);
       
        if (!$formIsValid) {
            
           return $this->renderAddReplyValidationFailed($form);
        } 
                   
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        $success = $service->addReply($user, $comment, $parentCommentId);
        if($success){
               
            return $this->renderReply($success, true);       
        } 
       
        $message = 'Reply not added';
           
        throw new Exception\DomainException($message);      
    }
    /**
     * array($userId, $parentCommentId, $comment);
     * @return array
     */
    public function getReplyFormParams()
    {
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $parentCommentId = $this->params()->fromPost('parent-id');
        $comment = $this->params()->fromPost('comment');  
      
        return array($userId, $parentCommentId, $comment);
    }

    
    /**
     * return array($userId, $moduleId, $comment, $title);
     * @return array
     */
    public function requestInfo()
    {
        $user = $this->zfcUserAuthentication()->getIdentity();
        $moduleId = $this->params()->fromPost('module-id','');
        $comment = $this->params()->fromPost('comment', '');
        $title = $this->params()->fromPost('title' ,'');
        
        return array($user, $moduleId, $comment, $title);
    }
    /**
     * edit comment, user must be logged in
     * @return Zend\View\Model\ViewModel
     * @throws \ZfModule\Controller\Exception\DomainException
     */
    public function editAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $commentId, $comment) = $this->requestInfo();
        
        $service = $this->getServiceLocator()->get('zfmodule_comment_service');
       
        $success = $service->edit($userId, $commentId, $comment);
        if($success){
            
            return $this->renderEditSuccess($success, true);
        }
        
        throw new \ZfModule\Controller\Exception\DomainException("Comment doesn't seem to have been removed");       
    }
    /**
     * remove a comment
     * @return mixed either redirect or view
     * @throws \ZfModule\Controller\Exception\RuntimeException
     */
    public function removeAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $commentId) = $this->requestInfo();
        $
        $canRemove = $this->commentsValidate()->removeIsValid($commentId);
        if ($canRemove) {
            $service = $this->getServiceLocator()->get('zfmodule_comment_service');
        
            $success = $service->remove($commentId);
            if($success){
            
                return $this->renderRemoveSuccess();
            }
        }
        
        
        throw new \ZfModule\Controller\Exception\DomainException("Comment doesn't seem to have been removed");  
    }
    
    /**
     * render either comment or reply sucedss view
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderEditSuccess($result, $terminal=true)
    {
        $viewParams = array('comment' => $result);
        if($result->hasParent()){
            
            return $this->renderComment($result);
        } else {
            
            return $this->renderReply($result);
        }
    }
    /**
     * render a generic this thing hs been removed message
     * @return Zend\View\Model\ViewModel
     * @todo create view model for this
     */
    public function renderRemoveSuccess()
    {
        //@todo    
    }
    /**
     * get('zfmodule_view_model_comment')
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderComment($result, $terminal=true)
    {
        $viewParams = array('comment' => $result);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_comment');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
   /**
     * get('zfmodule_view_model_reply')
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel
     */
    public function renderReply($result, $terminal=true)
    {        
        $viewParams = array('comment' => $result);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_reply');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }    
}
