<?php

namespace ZfModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;


/**
 * Description of CommentController
 *
 * @author gerard
 */
class CommentController extends AbstractActionController
{
    
    private $addReplySuccessTemplate = 'zf-module/helper/child-comment';
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

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
        $userId = 2;
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        try {
             $success = $service->add($userId, $moduleId, $comment, $title);
        } catch (Exception $exc) {
            $this->renderAddExeception($exc);
        }

        return $this->renderAddSuccess();        
        
    }
    public function addReplyAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
         //   return $this->redirect()->toRoute('zfcuser/login');
        }
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $parentCommentId = $this->params()->fromPost('parent-comment-id');
        $comment = $this->params()->fromPost('comment');  
        
        $service = $this->getServiceLocator()->get('zfmodule_service_comment');
        try {
            $userId =2;
            $parentCommentId=1;
           
             $success = $service->addReply($userId, $comment, $parentCommentId);
        } catch (Exception $exc) {
            $this->renderAddExeception($exc);
        }
      $template =  $this->getAddReplySuccessTemplate();
        return $this->renderAddReplySuccess(array('comment' => $success), true, $template );       
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
        } catch (Exception $exc) {
            $this->renderEditExeception($exc);
        }

        return $this->renderEditSuccess();  
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
        } catch (Exception $exc) {
            $this->renderRemoveExeception($exc);
        }

        return $this->renderRemoveSuccess();  
    }
    public function renderEditSucess()
    {
        return $this->renderDefaultSucess();
    }
    public function renderRemoveSucess()
    {
        return $this->renderDefaultSucess();
    }
   
     public function renderAddReplySuccess($result, $termial=false,  $template='')
    {
        return $this->renderDefaultSuccess($result, true, $template);
    }
    public function renderAddSucess()
    {
        return $this->renderDefaultSuccess();
    }
    public function renderDefaultSuccess($viewParams, $terminal=false, $template='')
    {
        $view = new ViewModel($viewParams);
        $view->setTerminal($terminal);
        if($template){
           $view->setTemplate($template); 
        }
        return $view;
    }
    public function renderEditException()
    {
        return $this->renderDefaultSucess();
    }
    public function renderRemoveException()
    {
        return $this->renderDefaultSucess();
    }
    public function renderAddException()
    {
        return $this->renderDefaultSucess();
    }
    public function renderDefaultException()
    {
        $view = new ViewModel(array('success' => 0));
        $view->setTerminal(true);
    }
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
    public function getAddReplySuccessTemplate(){
        return $this->addReplySuccessTemplate;
    }
    
}

?>
