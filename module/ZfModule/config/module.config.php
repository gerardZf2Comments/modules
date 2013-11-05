<?php
return array(
     'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'zfmodule_entity_module' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/module'
            ),
            'zfmodule_entity_tag' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/tag'
            ),
            'zfmodule_entity_comment'  => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/comment'
            ),
           
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'ZfModule\Entity\Module'  => 'zfmodule_entity_module',
                    'ZfModule\Entity\Tag'  => 'zfmodule_entity_tag',
                    'ZfModule\Entity\Comment'  => 'zfmodule_entity_comment',
                    
                )
            ),
            
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'ZfModule\Controller\TagController' => 'ZfModule\Controller\TagController', 
            'ZfModule\Controller\Index' => 'ZfModule\Controller\IndexController',
            'ZfModule\Controller\Repo' => 'ZfModule\Controller\RepoController',
            'ZfModule\Controller\Comment' => 'ZfModule\Controller\CommentController',
            'ZfModule\Controller\Search' => 'ZfModule\Controller\SearchController',
            'ZfModule\Controller\SearchIndexer' => 'ZfModule\Controller\SearchIndexerController',
        ),
        'aliases' => array(
           'ZfModule\Controller\Tag' =>'ZfModule\Controller\TagController', 
            
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
          'view-module' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => 'index/all',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\SearchIndexer',
                        'action' => 'all',
                    ),
                ),
            ),
            )
        )
     ),

    'router' => array(
        'routes' => array(
            'view-module' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:vendor/:module',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\Index',
                        'action' => 'view',
                    ),
                ),
            ),
            //search routes
                    'gol-search' => array(
                'type' => 'Literal',
                
                'options' => array(       
                    'route' => '/search',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\Search',
                        'action' => 'module',
                    ),
                ),
                'priority' => 1,
                'may_terminate' => true,
                'child_routes' => array(
                    'tag' => array(
                         'type' =>  'Segment',
                       'options' => array( 
                       
                        'route' => '/tag[/:query][/:page][/:limit]',
                        'defaults' => array(
                            'controller' => 'ZfModule\Controller\Search',
                            'action' => 'tag',
                        ),
                    ),
                        ),
                    'tag-ajax' => array(
                        'type' =>  'segment',
                        'options' => array(
                        'route' => '/tag-ajax[/:query][/:page][/:limit]',
                        'defaults' => array(
                            'controller' => 'ZfModule\Controller\Search',
                            'action' => 'tagAjax',
                        ),
                    ),
                        ),
                    'module' => array(
                        'type' =>  'segment',
                        'options' => array(
                        'route' => '/module[/:query][/:page][/:limit]',
                        'defaults' => array(
                            'controller' => 'ZfModule\Controller\Search',
                            'action' => 'module',
                        ),
                            ),
                    ),
                    'module-ajax' => array(
                        'type' =>  'segment',
                        'options' => array(
                        'route' => '/module-ajax[/:query][/:page][/:limit]',
                        'defaults' => array(
                            'controller' => 'ZfModule\Controller\Search',
                            'action' => 'moduleAjax',
                        ),
                    ),
                        ),
                ),
            ),
             // end search routes
            // comment routes 
            'comment' => array(
                'type' => 'Segment',
                'options' => array (
                    'route' => '/comment',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\Comment',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'priority' => 1000,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'add-reply' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-reply',
                            
                            'defaults' => array(
                                'action' => 'addReply',
                            ),
                        ),
                    ),
                    'remove' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/remove',
                            
                            'defaults' => array(
                                'action' => 'remove',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit',
                            
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                  ),
                ),
            // end comment routes 
             // tag routes 
            'tag' => array(
                'type' => 'Segment',
                'options' => array (
                    'route' => '/tag',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\TagController',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'priority' => 1000,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                   
                    'remove' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/remove',
                            
                            'defaults' => array(
                                'action' => 'remove',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit',
                            
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                  ),
                ),
            // end tag routes 
                    
                    //zf-module route 
            'zf-module' => array(
                'type' => 'Segment',
                'options' => array (
                    'route' => '/module',
                    'defaults' => array(
                        'controller' => 'ZfModule\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/:owner]',
                            'constrains' => array(
                                'owner' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'action' => 'organization',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    'remove' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/remove',
                            'defaults' => array(
                                'action' => 'remove',
                            ),
                        ),
                    ),
                ),
            ),
            // end zf-module route 
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'zf-module' => __DIR__ . '/../view',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'newModule' => 'ZfModule\View\Helper\NewModule',
            'listModule' => 'ZfModule\View\Helper\ListModule',
            'moduleView' => 'ZfModule\View\Helper\ModuleView',
            'moduleDescription' => 'ZfModule\View\Helper\ModuleDescription',
        ),
        
    ),
    'zfmodule' => array(
        /**
         * Cache configuration
         */
        'cache' => array(
            'adapter'   => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => dirname(__FILE__). '/../../../data/search',
                    'writable' => true,
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => true),
                'serializer'
            )
        ),
        'cache_key' => 'zfmodule_app',
    ),
);
