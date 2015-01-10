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
class Comments extends AbstractHelper implements ServiceLocatorAwareInterface
{
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
     * @param int $moduleId
     * @param int $limit 
     * @param string $order
     * @param string $sort
     */
    public function __invoke($moduleId, $limit = 15, $order=null, $sort =null)
    {        
        $service = $this->getCommentService();
        $comments = $service->commentsByModuleId($moduleId, $limit, $sort, $order);
         $vm = new ViewModel(array(
            'comments' => $comments,
             'moduleId' => $moduleId,
        ));
        $vm->setTemplate('zf-module/comment/comments.phtml');
        
        $commentForm = $this->getServiceLocator()->getServiceLocator()->get('zfmodule_view_model_comment_form');
        $replyForm = $this->getServiceLocator()->getServiceLocator()->get('zfmodule_view_model_comment_reply_form');
        $commentForm->setVariable('moduleId', $moduleId);
        $vm->addChild($commentForm, 'commentForm');
        $vm->addChild($replyForm, 'replyForm');
        return $this->getView()->render($vm);
        
    }
    public function setCommentService($cs){
        $this->commentService = $cs;
        return $this;
    }
    /**
     * 
     * @return \ZfModule\Service\Comment
     */
    public function getCommentService(){
        return $this->commentService;
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


