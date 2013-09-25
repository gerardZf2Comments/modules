<?php

namespace ZfModule\View\Helper;


use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of Comments
 *
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
        ));
        $vm->setTemplate('zf-module/helper/comments.phtml');


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

?>
