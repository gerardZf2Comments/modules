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
            'ZfModule\Controller\Index' => 'ZfModule\Controller\IndexController',
            'ZfModule\Controller\Repo' => 'ZfModule\Controller\RepoController',
            'ZfModule\Controller\Comment' => 'ZfModule\Controller\CommentController'
        ),
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
            // comment route 
            'comment' => array(
                'type' => 'Segment',
                'options' => array (
                    'route' => '/comment[/:module-id]',
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
