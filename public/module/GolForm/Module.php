<?php
namespace GolForm;

/**
 * Description of Module
 *
 * @author gerard
 */
class Module
{
    public function getAutoloaderConfig()
    {
        return array(
           
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
