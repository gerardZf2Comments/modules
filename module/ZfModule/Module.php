<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *another change
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfModule;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Cache\StorageFactory;

/**
 * module loader class
 */
class Module implements AutoloaderProviderInterface {

    /**
     * declare namespaces
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    /**
     * return include __DIR__ . '/config/module.config.php';
     * @return array
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * bootstrap event handler
     * @param  Zend\EventManager\Event $e
     */
    public function onBootstrap($e) {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $em = $e->getApplication()->getEventManager()->getSharedManager();
        $em->attach('ZfModule\Controller\SearchController', 'insert.post', function($e) use ($sm) {
                    $indexer = $sm->get('zfmodule_service_search_module_indexer');
                    //   $indexer->addExtraModule();
                });
    }

    /**
     * description
     * @return array
     */
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'zfmodule_cache' => function($sm) {
                    $config = $sm->get('Config');
                    $storage = StorageFactory::factory($config['zfmodule']['cache']);

                    return $storage;
                },
                'zfmodule_service_search_module_cache' => function($sm) {
                    $cache = $sm->get('zfmodule_cache');
                    $options = new \ZfModule\Options\ModuleOptions();
                    $moduleCache = new \ZfModule\Service\Search\ModuleCache($cache, $options, $sm);

                    return $moduleCache;
                },
                'zfmodule_mapper_module' => function ($sm) {
                    $options = new \ZfModule\Options\ModuleOptions();
                    $options->setModuleEntityClass('ZfModule\Entity\Module');
                    return new \ZfModule\Mapper\DocModule(
                            $sm->get('doctrine.entitymanager.orm_default'), $options
                    );
                },
                'zfmodule_entity_comment' => function ($sm) {
                    $entity = new \ZfModule\Entity\Comment;
                    return $entity;
                },
                'zfmodule_mapper_tag' => function ($sm) {
                    $options = new \ZfModule\Options\ModuleOptions();
                    $options->setTagEntityClassName('ZfModule\Entity\Tag');
                    return new \ZfModule\Mapper\Tag(
                            $sm->get('doctrine.entitymanager.orm_default'), $options
                    );
                },
                'zfmodule_mapper_comment' => function ($sm) {
                    $options = new \Comments\Options\CommentOptions();
                    $options->setCommentEntityClassName('Comments\Entity\Comment');
                    $options->setUserEntityClassName('User\Entity\User');
                    return new \ZfModule\Mapper\Comment(
                            $sm->get('doctrine.entitymanager.orm_default'), $options
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
                    $service = new Service\Module;

                    return $service;
                },
                'zfmodule_service_module_search' => function($sm) {
                    $service = new \ZfModule\Service\Search\ModuleSearch();
                    $options = new \ZfModule\Options\ModuleOptions();
                    $service->setOptions($options);
                    $service->setServiceLocator($sm);

                    return $service;
                },
                'zfmodule_view_model_exception' => function($sm) {
                    $viewModel = new \ZfModule\View\Model\Exception();
                    $options = new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewExceptionTemplateName();
                    $template = 'zf-module/exception/base';
                    $viewModel->setTemplate($template);

                    return $viewModel;
                },
                'zfmodule_view_model_comment' => function($sm) {
                    $viewModel = new \ZfModule\View\Model\Comment\Comment();
                    $options = new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddSuccessTemplateName();
                    $template = 'zf-module/comment/comment';
                    $viewModel->setTemplate($template);
                    $replyFormView = $sm->get('zfmodule_view_model_comment_reply_form');
                    $viewModel->addChild($replyFormView, 'replyForm');

                    return $viewModel;
                },
                'zfmodule_view_model_comment_form' => function($sm) {
                    $viewModel = new \Zend\View\Model\ViewModel;
                    // $options = new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddSuccessTemplateName();
                    $template = 'zf-module/comment/comment-form';
                    $viewModel->setTemplate($template);
                    $viewModel->setVariable('commentForm', $sm->get('zfmodule_form_comment_form'));
                    
                    return $viewModel;
                },
                'zfmodule_view_model_comment_reply_form' => function($sm) {
                    $viewModel = new \Zend\View\Model\ViewModel;
                    // $options = new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddSuccessTemplateName();
                    $template = 'zf-module/comment/reply-form';
                    $viewModel->setTemplate($template);
                    $viewModel->setVariable('replyForm', $sm->get('zfmodule_form_comment_reply_form'));
                    return $viewModel;
                },
                'zfmodule_view_model_tag_wrapper' => function($sm) {
                    $tagWrapper = new \Zend\View\Model\ViewModel();
                    $tagWrapper->setTemplate('zf-module/tag/tag-wrapper');
                    return $tagWrapper;
                },
                'zfmodule_view_model_tag_container' => function($sm) {
                    $viewModel = new \Zend\View\Model\ViewModel;
                    // $options =   new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddSuccessTemplateName();
                    $template = 'zf-module/tag/tag-container';
                    $viewModel->setTemplate($template);
                    return $viewModel;
                },
                'zfmodule_view_model_tag_form' => function($sm) {
                    $viewModel = new \Zend\View\Model\ViewModel;
                    // $options =   new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddSuccessTemplateName();
                    $template = 'zf-module/tag/form-container';
                    $viewModel->setTemplate($template);
                    $viewModel->setVariable('form', $sm->get('zfmodule_form_tag_form'));
                    return $viewModel;
                },
                'zfmodule_form_comment_reply_form' => function($sm) {
                    $form = new \ZfModule\Form\CommentReply();

                    return $form;
                },
                'zfmodule_tag_form' => function($sm) {
                    $form = new \ZfModule\Form\Tag();
                    
                    return $form;
                },
                
                'zfmodule_form_comment_form' => function($sm) {
                    $form = new \ZfModule\Form\Comment();

                    return $form;
                },
                'zfmodule_view_model_reply' => function($sm) {
                    $viewModel = new \ZfModule\View\Model\Comment\Reply();
                    $options = new \ZfModule\Options\ModuleOptions();
                    // $template =  $options->getViewAddReplySuccessTemplateName();
                    $template = 'zf-module/comment/child-comment';
                    $viewModel->setTemplate($template);

                    return $viewModel;
                },
                'zfmodule_service_search_module_search' => function($sm) {
                    $service = new \ZfModule\Service\Search\ModuleSearch();
                    $service->setServiceLocator($sm);
                    /** @todo replace all these new Options with ->get('')
                      $options = $sm->get('')
                     */
                    $options = new \ZfModule\Options\ModuleOptions();
                    $service->setOptions($options);
                    return $service;
                },
                'zfmodule_service_search_module_indexer' => function($sm) {
                    $service = new \ZfModule\Service\Search\ModuleIndexer;
                    $service->setServiceLocator($sm);
                    /** @todo replace all these new Options with ->get('')
                      $options = $sm->get('')
                     */
                    $options = new \ZfModule\Options\ModuleOptions();
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
            /* 'github_client' => function($sm) {
              $hybridAuth = $sm->get('HybridAuth');
              $adapter = $hybridAuth->getAdapter('github');
              $token = $adapter->getAccessToken();

              $client = $sm->get('EdpGithubClient');
              $client->authenticate('url_token',$token['access_token'], null);
              return $client;
              } */
            ),
        );
    }

    /**
     * description
     * @return array
     */
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                // todo - complete refactor
                'zfmoduleComments' => function ($sm) {
                    $helper = new \ZfModule\View\Helper\Comments;
                    $helper->setCommentService($sm->getServiceLocator()->get('zfmodule_service_comment'));
                    return $helper;
                },
                'ModuleTags' => function($sm) {
                    $viewHelper = new \ZfModule\View\Helper\ModuleTags();
                    $tagWrapper = $sm->getServiceLocator()->get('zfmodule_view_model_tag_wrapper');
                    $viewHelper->setTagWrapper($tagWrapper);
                    $tagContainer = $sm->getServiceLocator()->get('zfmodule_view_model_tag_container');
                    $viewHelper->setTagContainer($tagContainer);
                    $formContainer = $sm->getServiceLocator()->get('zfmodule_view_model_tag_form');
                    $viewHelper->setFormContainer($formContainer);
                    return $viewHelper;
                },
                'DisplayModuleTags' => function($sm) {
                    $viewHelper = new \ZfModule\View\Helper\TagDisplay();                    
                    return $viewHelper;
                },
                'TagForm' => function($sm) {
                    $viewHelper = new \ZfModule\View\Helper\TagForm();   
                    $viewHelper->setForm($sm->getserviceLocator()->get('zfmodule_tag_form'));
                    return $viewHelper;
                },
            )
        );
    }

}
