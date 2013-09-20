<?php
ini_set('display_errors', 1);
require(__DIR__.'/constants.php');

return array(
    'modules' => array(        
        'DoctrineModule',
     'DoctrineORMModule',
        'LfjErrorLayout',
        'ZfcBase',
        'ZfcUser',
        'ScnSocialAuth',
        'HybridAuth',
        'EdpGithub',
        'Application',
        'AssetManager',
        'Assetic',
		'User',
        'EdpModuleLayouts',
        'ZfModule',
        'EdpMarkdown',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'my_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    'path/to/my/entities',
                    'another/path'
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                  //  'My\Namespace' => 'my_annotation_driver'
                )
            )
        )
    ),
     'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'fuckit2004',
                    'dbname'   => 'modules.w.doctrine',
                )
            )
        )
    ),
);
