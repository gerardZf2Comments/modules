<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfModule;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Cache\StorageFactory;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'zfmodule_cache' => function($sm) {
                    $config = $sm->get('Config');
                    $storage = StorageFactory::factory($config['zfmodule']['cache']);

                    return $storage;
                },
               'zfmodule_mapper_module' => function ($sm) {
                    $options =   new \ZfModule\Options\ModuleOptions();
                    $options->setModuleEntityClass('ZfModule\Entity\Module');
                    return new \ZfModule\Mapper\DocModule(
                            
                        $sm->get('doctrine.entitymanager.orm_default'),
                     $options
                    );
                },
                'zfmodule_entity_comment'  => function ($sm) {
                   $entity = new \ZfModule\Entity\Comment;
                   return $entity;
                },
                'zfmodule_mapper_tag' => function ($sm) {
                    $options =   new \ZfModule\Options\ModuleOptions();
                    $options->setTagEntityClassName('ZfModule\Entity\Tag');
                    return new \ZfModule\Mapper\Tag(                            
                        $sm->get('doctrine.entitymanager.orm_default'),
                     $options
                    );
                },
                'zfmodule_mapper_comment' => function ($sm) {
                    $options =   new \ZfModule\Options\ModuleOptions();
                    $options->setCommentEntityClassName('ZfModule\Entity\Comment');
                    $options->setUserEntityClassName('ZfcUserDoctrineORM\Entity\User');
                    return new \ZfModule\Mapper\Comment(                            
                        $sm->get('doctrine.entitymanager.orm_default'),
                     $options
                    );
                },
                        /*
                'zfmodule_mapper_module' => function ($sm) {
                    $mapper = new Mapper\Module();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $mapper->setEntityPrototype(new Entity\Module);
                    $mapper->setHydrator(new Mapper\ModuleHydrator());
                    return $mapper;
                },
                         */
                'zfmodule_service_module' => function($sm) {
                    $service = new  Service\Module;
                   
                    return $service;
                },
                  'zfmodule_service_module_search' => function($sm) {
                      $service = new \ZfModule\Service\Search\ModuleSearch();
                      $options =   new \ZfModule\Options\ModuleOptions();
                      $service->setOptions($options);
                      $service->setServiceLocator($sm);
                      return $service;
                  },
                  'zfmodule_view_model_exception' =>function($sm){
                      $viewModel = new \ZfModule\View\Model\Exception();
                      $options =   new \ZfModule\Options\ModuleOptions();
                      // $template =  $options->getViewExceptionTemplateName();
                      $template = 'zf-module/exception/base';
                      $viewModel->setTemplate($template);
                      
                      return $viewModel;
                      },
                    'zfmodule_view_model_comment' =>function($sm)
                    {
                        $viewModel = new \ZfModule\View\Model\Comment\Comment();
                        $options =   new \ZfModule\Options\ModuleOptions();
                         // $template =  $options->getViewAddSuccessTemplateName();
                        $template = 'zf-module/comment/comment';
                        $viewModel->setTemplate($template);
                        $replyFormView = $sm->get('zfmodule_view_model_comment_reply_form');
                        $viewModel->addChild($replyFormView, 'replyForm');
                      
                        return $viewModel;
                    },
                    'zfmodule_view_model_comment_form' =>function($sm)
                    {
                     //   $viewModel = new \ZfModule\View\Model\Comment\CommentForm();
                         $viewModel = new \Zend\View\Model\ViewModel;
                        $options =   new \ZfModule\Options\ModuleOptions();
                         // $template =  $options->getViewAddSuccessTemplateName();
                        $template = 'zf-module/comment/comment-form';
                        $viewModel->setTemplate($template);
                        $viewModel->setVariable('commentForm', $sm->get('zfmodule_form_comment_form'));
                        return $viewModel;
                    },
                    'zfmodule_view_model_comment_reply_form' =>function($sm)
                    {
                       // $viewModel = new \ZfModule\View\Model\Comment\ReplyForm();
                         $viewModel = new \Zend\View\Model\ViewModel;
                        $options =   new \ZfModule\Options\ModuleOptions();
                         // $template =  $options->getViewAddSuccessTemplateName();
                        $template = 'zf-module/comment/reply-form';
                        $viewModel->setTemplate($template);
                        $viewModel->setVariable('replyForm', $sm->get('zfmodule_form_comment_reply_form'));
                        return $viewModel;
                    },
                    'zfmodule_form_comment_reply_form' => function($sm){
                        $form = new \ZfModule\Form\CommentReply();
                        
                        return $form;
                    },
                    'zfmodule_form_comment_form' => function($sm){
                        $form = new \ZfModule\Form\Comment();
                        
                        return $form;
                    },
                    'zfmodule_view_model_reply' =>function($sm)
                    {
                        $viewModel = new \ZfModule\View\Model\Comment\Reply();
                        $options =   new \ZfModule\Options\ModuleOptions();
                         // $template =  $options->getViewAddReplySuccessTemplateName();
                        $template = 'zf-module/comment/child-comment';
                        $viewModel->setTemplate($template);
                      
                        return $viewModel;
                    },
                    'zfmodule_service_search_module_search' => function($sm){
                        $service = new \ZfModule\Service\Search\ModuleSearch();
                        $service->setServiceLocator($sm);
                        /** @todo replace all these new Options with ->get('')
                          $options = $sm->get('')
                          */
                        $options =   new \ZfModule\Options\ModuleOptions();
                        $service->setOptions($options);
                        return $service;
                    },
                    'zfmodule_service_search_module_indexer' => function($sm){
                        $service = new \ZfModule\Service\ModuleIndexer;
                        $service->setServiceLocator($sm);
                        /** @todo replace all these new Options with ->get('')
                          $options = $sm->get('')
                          */
                        $options =   new \ZfModule\Options\ModuleOptions();
                        $service->setOptions($options);
                        return $service;
                    },
                    'zfmodule_service_comment' => function($sm) {
                        $service = new Service\Comment();
                        $service->setServiceLocator($sm);
                   
                        return $service;
                },
                'zfmodule_service_repository' => function($sm) {
                    $service = new Service\Repository;
                    $service->setApi($sm->get('EdpGithub\Client'));
                    return $service;
                },
                /*'github_client' => function($sm) {
                    $hybridAuth = $sm->get('HybridAuth');
                    $adapter = $hybridAuth->getAdapter('github');
                    $token = $adapter->getAccessToken();

                    $client = $sm->get('EdpGithubClient');
                    $client->authenticate('url_token',$token['access_token'], null);
                    return $client;
                }*/
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'zfmoduleComments' => function ($sm) {
                    $helper = new \ZfModule\View\Helper\Comments;
                    $helper->setCommentService($sm->getServiceLocator()->get('zfmodule_service_comment'));
                    return $helper;
                }
            )
        );
    }
}
