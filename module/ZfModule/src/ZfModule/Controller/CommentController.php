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
   public function __construct()
    {
       $brk=1;
    }

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
        list($userId, $moduleId, $comment, $title) = $this->requestInfo();           
             
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
     
        $form = new \ZfModule\Form\Comment();
        $formIsValid = $this->validateCommentForm($form, $moduleId, $comment, $title);
          
        if(!$form->isValid()){
              
           return $this->renderAddValidationFailed($form);
        } 
            
        $userId= 1;
        $success = $service->add($userId, $moduleId, $comment, $title);         
       
        // it seems the add function either returns a collection or something else
        if($success){
            $success = $success;
            return $this->renderComment($success, true); 
        }
        
        $message = 'Comment not added';
        throw new Exception\DomainException($message);
        
    }
    /**
     * set data & valiadte comment form
     * @param Zend\Form\Form $form
     * @param int $moduleId
     * @param string $comment
     * @param string $title
     * @return bool
     */
    public function validateCommentForm($form, $moduleId, $comment, $title)
    {
        $form->setData(array(
            'module-id' => $moduleId,
            'comment'=> $comment,
            'title' => $title,               
        ));
        
        return $form->isValid();
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
        list($userId, $parentCommentId, $comment) = $this->getReplyFormParams();
        
        $form = new \ZfModule\Form\CommentReply();
        $formIsValid = $this->validateReplyForm($form, $parentCommentId, $comment);
       
        if (!$formIsValid) {
            
           return $this->renderAddReplyValidationFailed($form);
        } 
        $userId =1;           
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        $success = $service->addReply($userId, $comment, $parentCommentId);
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
     * set data and call is valid
     * @param Zend\Form\Form $form
     * @param int $parentCommentId
     * @param string $comment
     * @return bool
     */
    public function validateReplyForm($form,  $parentCommentId, $comment) 
    {
        $form->setData(array(
            'module-id' => $parentCommentId,
            'comment'=> $comment,               
        ));
        
        return $form->isValid();
    }

    /**
     * return array($userId, $moduleId, $comment, $title);
     * @return array
     */
    public function requestInfo()
    {
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $moduleId = $this->params()->fromPost('module-id','');
        $comment = $this->params()->fromPost('comment', '');
        $title = $this->params()->fromPost('title' ,'');
        
        return array($userId, $moduleId, $comment, $title);
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
       
        list($userId, $commentId, $comment) = $this->requestInfo();
        
        $this->validateRemoveParams($userId, $commentId);
        
        $service = $this->getServiceLocator()->get('zfmodule_comment_service');
        
        $success = $service->remove($userId, $commentId);
        if($success){
            
            return $this->renderRemoveSuccess();
        }
        
        throw new \ZfModule\Controller\Exception\DomainException("Comment doesn't seem to have been removed");  
    }
    /**
     * check we have ints for params
     * @param int $userId
     * @param int $commentId
     * @return boolean true
     * @throws \ZfModule\Controller\Exception\InvalidArgumentException
     * @todo use a vailator chain
     */
    public function validateRemoveParams($userId, $commentId)
    {
        $message = array();
        if(!is_int($userId)){
            $message[] = "User not found.";
        }
        if(!is_int($commentId)){
            $message[] = "Comment not found in request.";
        }
        if($message){
            $message = implode(' ', $message);
            
            throw new \ZfModule\Controller\Exception\InvalidArgumentException($message);
        }
        return true;
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

?>
