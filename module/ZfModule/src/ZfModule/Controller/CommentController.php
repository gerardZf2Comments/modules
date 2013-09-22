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
            return $this->redirect()->toRoute('zfcuser/login');
        }
        list($userId, $moduleId, $comment) = $this->basicInfo();
        
        $service = $this->getServiceLocator()->get('zfmodule_comment_service');
        try {
             $success = $service->add($userId, $moduleId, $comment);
        } catch (Exception $exc) {
            $this->renderAddExeception($exc);
        }

        return $this->renderAddSuccess();        
        
    }
    /**
     * gets info frm params and auth // return array($userId, $moduleId, $comment);
     * return array
     */
    public function basicInfo()
    {
        $userId = $this->zfcUserAuthentication()->getIdentity();
        $moduleId = $this->params()->fromPost('module_id');
        $comment = $this->params()->fromPost('user_id');
        
        return array($userId, $moduleId, $comment);
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
    public function renderAddSucess()
    {
        return $this->renderDefaultSucess();
    }
    public function renderDefaultSucess()
    {
        $view = new ViewModel(array('success' => 1));
        $view->setTerminal(true);
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
    
}

?>
