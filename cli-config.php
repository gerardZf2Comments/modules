
<?php
$zf2Path = '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/vendor/ZF2/library';
require_once  $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        \Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                   'ZendDeveloperTools' =>   $zf2Path . '/../../../module/ZendDeveloperTools/src/ZendDeveloperTools',
                   'Symfony' => '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/vendor/symfony/console/Symfony'
                    ),
            ),
        ));
$appConfig = include __DIR__. '/config/application.config.php'; 
 
        if (1) {
            $consoleServiceConfig = array(
                'service_manager' => array(
                    'factories' => array(
                        'ServiceListener' => 'Zend\Test\PHPUnit\Mvc\Service\ServiceListenerFactory',
                    ),
                ),
            );
            $appConfig = array_replace_recursive($appConfig, $consoleServiceConfig);
        }
        
$app =  \Zend\Mvc\Application::init($appConfig);
$em = $app->getServiceManager()->get('doctrine.entitymanager.orm_default');

require_once '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => 'database.sqlite'
);

//$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
//$w = include  '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/vendor/symfony/console/Symfony/Component/Console/Helper/HelperSet.php';
return $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));