<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * This autoloading setup is really more complicated than it needs to be for most
 * applications. The added complexity is simply to reduce the time it takes for
 * new developers to be productive with a fresh skeleton. It allows autoloading
 * to be correctly configured, regardless of the installation method and keeps
 * the use of composer completely optional. This setup should work fine for
 * most users, however, feel free to configure autoloading however you'd like.
 */

// Composer autoloading
// if you installed zf2 via composer the namespace will be included
// during this process 
// this loader is called before the zend loader so if a namespace of the zend library 
// is declared here it will be loaded from here this includes zend sub-components  
// installed while using composer to install the likes of doctrine module

//if you need to resolve differences you should read the composer.json of the package 
//that required the component and check if your alternative zf2 package is compatable.
//possibly you could remove the dependency declaration from the composer.json package
//or simply comment the unneccessary lines (in either autoload_namespace or 
//autoload_classmap) and put it in git for management
chdir(__DIR__);
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}
$zf2Path ='';
if( getenv('ZF2_PATH') ){
    $zf2Path = getenv('ZF2_PATH');
}
// this is the git submodule location if you install via
// a git clone --recursive or submodule update --init
// it will be used not the environment variable
if(is_dir('vendor/ZF2/library')){
    $zf2Path = 'vendor/ZF2/library';
}
// even if the namespace for zend has been defined in the composer autoloader
// you can declare other namespaces here.
$namespaces = array(
                    'ZendSearch' => __DIR__ .'/'.$zf2Path . '/ZendSearch'
              );
if($zf2Path){
    //if the namespace for zend has been declared in the composer autoloader 
    //it will load the zend libraries and this ( 'autoregister_zf' => true, )
    //will be redundant
 include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => $namespaces,
            )
        ));
}
if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}