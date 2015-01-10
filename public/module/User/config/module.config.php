<?php
return array(
     'doctrine' => array(
        'driver' => array(
            'user_entity_user' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/user'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'User\Entity\User'  => 'user_entity_user'
                )
            )
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'helper/module'                 =>  __DIR__ . '/../view/helper/module.phtml',
            'scn-social-auth/user/login'    =>  __DIR__ . '/../view/scn-social-auth/user/login.phtml',
            'user/helper/new-users'         =>  __DIR__ . '/../view/user/helper/new-users.phtml',
            'user/module/orgs'              =>  __DIR__ . '/../view/user/module/orgs.phtml',
            'user/module/repos'             =>  __DIR__ . '/../view/user/module/repos.phtml',
            'zfc-user/user/index'           =>  __DIR__ . '/../view/zfc-user/user/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Module' => 'User\Controller\ModuleController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'UserRepositories' => 'User\View\Helper\UserRepositories',
            'newUsers' => 'User\View\Helper\NewUsers',
            'userOrganizations' => 'User\View\Helper\UserOrganizations',
        ),
    ),
);
