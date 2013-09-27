<?php

namespace ZfModule\Controller;

use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfModule\View\Model\Exception;


/**
 * Description of CommentController
 *@todo validate input, throw and catch exceptions, test
 * @author gerard
 */
class CommentController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
    
    protected $em;
*/

    /**
     * 
     * add comment, user must be logged in
     */
    public function addAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
          //  return $this->redirect()->toRoute('zfcuser/login');
        }
        list($userId, $moduleId, $comment, $title) = $this->basicInfo();           
             
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        try {
             $form = new \ZfModule\Form\Comment();
            $form->setData(array(
                'module-id' => $moduleId,
                'comment'=> $comment,
                'title' => $title,
               
            ));
            $userId= 2;
            if(!$form->isValid()){
               $form->setMessages(array('error message'));
                return $this->renderAddValidationFailed($form);
            } 
            
            
             $success = $service->add($userId, $moduleId, $comment, $title);
        } catch (Exception\InvalidArgumentException $exc) {
           
            $response = $this->getResponse();
            $response->setStatusCode(500);
            
            return $this->renderAddException($exc, true);
        }
        catch (Exception2 $exc) {
           
            $response = $this->getResponse();
            $response->setStatusCode(500);
           
            return $this->renderAddExecption($exc, true);
        }
       if(is_array($success) && isset($success[0])){
           $success = $success[0];
       }
        
        return $this->renderComment($success, true);
           
        
    }
    public function renderAddReplyValidationFailed($form, $terminal=true)
    {
        $viewParams = array('replacementForm' => $form);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_comment_reply_form');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
     public function renderAddValidationFailed($form, $terminal=true)
    {
        $viewParams = array('replacementForm' => $form);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_comment_form');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }

    public function addReplyAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            //return $this->redirect()->toRoute('zfcuser/login');
        }
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $parentCommentId = $this->params()->fromPost('parent-id');
        $comment = $this->params()->fromPost('comment');  
        
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        try {
            $form = new \ZfModule\Form\CommentReply();
            $form->setData(array(
                'module-id' => $parentCommentId,
                'comment'=> $comment,
               
            ));
            $userId= 2;
            if(!$form->isValid()){
            
                return $this->renderAddReplyValidationFailed($form);
            } 
            $userId =2;
            if(!$userId || !$parentCommentId || !$comment){
                throw new Exception\InvalidArgumentException('details missings');
            }
            
             $success = $service->addReply($userId, $comment, $parentCommentId);
             if(is_array($success) && isset($success[0])){
                $success = $success[0];
             }
         } catch (Exception\InvalidArgumentException $exc) {
           
            $response = $this->getResponse();
            $response->setStatusCode(500);
            
            return $this->renderAddException($exc, true);
        }
        catch (Exception2 $exc) {
           
            $response = $this->getResponse();
            $response->setStatusCode(500);
           
            return $this->renderAddExecption($exc, true);
        }
     
        
        
        return $this->renderReply($success, true);       
    }

    /**
     * gets info frm params and auth // return array($userId, $moduleId, $comment);
     * return array
     */
    public function basicInfo()
    {
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $moduleId = $this->params()->fromPost('module-id','');
        $comment = $this->params()->fromPost('comment', '');
        $title = $this->params()->fromPost('title' ,'');
        
        return array($userId, $moduleId, $comment, $title);
    }
     /**
     * 
     * edit comment, user must be logged in
     */
    public function editAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $moduleId, $comment) = $this->basicInfo();
        
        $service = $this->getServiceLocator()->get('zfmodule_comment_service');
        try {
             $success = $service->add($userId, $moduleId, $comment);
        } catch (Exception2 $exc) {
            $this->renderEditExeception($exc);
        }

        return $this->renderComment($success);   
       
    }
    /**
     * 
     * remove comment, user must be logged in
     */
    public function removeAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
       
        list($userId, $moduleId, $comment) = $this->basicInfo();
        
        $service = $this->getServiceLocator()->get('zfmodule_comment_service');
        try {
             $success = $service->add($userId, $moduleId, $comment);
        } catch (Exception2 $exc) {
            $this->renderRemoveExeception($exc);
        }

        return $this->renderRemoveSuccess();  
    }
    /**
     * 
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel;
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
     * 
     * @param type $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel;
     */
    public function renderRemoveSuccess($result, $terminal=true)
    {
        $viewParams = array('removed' => $result);
        return $this->renderDefaultSuccess($viewParams, $terminal=true);
    }
    /**
     * 
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel;
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
     * 
     * @param entity $result
     * @param boolean $terminal
     * @return Zend\View\Model\ViewModel;
     */
    public function renderReply($result, $terminal=true)
    {        
        $viewParams = array('comment' => $result);
        $view = $this->getServiceLocator()->get('zfmodule_view_model_reply');
        $view->setVariables($viewParams);
        $view->setTerminal($terminal);
        
        return $view;
    }
   
    public function renderEditException(Exception $exc, $terminal = false)
    {
        return $this->renderException( $exc, $terminal = false);
    }
    /**
     * 
     * @param Exception $exc
     * @param bool $terminal
     * @return ViewModel
     */
    public function renderRemoveException(Exception $exc, $terminal = false)
    {
        return $this->renderException( $exc, $terminal = false);
    }
    /**
     * 
     * @param Exception $exc
     * @param bool $terminal
     * @return ViewModel
     */
    public function renderAddException(Exception $exc, $terminal = false)
    {
        return $this->renderException($exc, $terminal);
    }
    /**
     * 
     * @param Exception $exc
     * @param bool $terminal

     * @return ViewModel
     */
    public function renderException( Exception $exc, $terminal = false)
    {
       $viewParams = array('exc' => $exc,'message' => $exc->getMessage());
       $view = $this->getServiceLocator()->get('zfmodule_view_model_exception');  
       $view->setVariables($viewParams);
       $view->setTerminal($terminal);
       return $view;
    }
    /*
    public function listAction()
    {
        $em = $this->getServiceLocator()->get('zfcuser_doctrine_em');
        
        $moduleId = (int) $this->params()->fromRoute('module-id');
        if(!$moduleId){
            throw new Exception;
        }
        try {
        
        $service = $this->getServiceLocator()->get('zfmodule_service_module');
        $comments = $service->commentsByModuleId($moduleId);      
        
        } catch (Exception $exc){
            
        }
        
        return array('comments' => $comments);
    }
   */ 
    
}

?>
