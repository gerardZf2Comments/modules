<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 This setup should work fine for
 * most users, however, feel free to configure autoloading however you'd like.
 */
chdir(__DIR__);
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}
if(is_dir('vendor/ZF2/library')){
    $zf2Path = 'vendor/ZF2/library';
}
if(getenv('ZF2_PATH')){
    $zf2Path = getenv('ZF2_PATH');
}
include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                 
                     'ZendSearch' => __DIR__.'/vendor/ZF2/library//ZendSearch'
                ),
            )
        ));

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
