<?php

use GolTest\PHPUnit\Database\DoctrineTestCase;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Mapping\Driver;



/** 
* Description of ControllerTest
 *
 * @author gerard
 */
class ExampleTest extends DoctrineTestCase
{
 
   /**
     * set the doctrine.configuration.orm_default
     * @todo unset mysql fk checks before parent::setUp
     * @todo then reset them
     */
    public function getApplicationConfig()
    {           
        $baseDir = '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com';
        
        return include $baseDir . '/config/application.config.php';
    }
    /**
     * this is an example test it just exists to show the basic format 
     * for intregration testing doctrine
     */
    public function testExample()
    {
        $em = $this->getEntityManager();
        $entity = $em->find('User\Entity\User', 1);          
        $userName = $entity->getUsername();
        $this->assertEquals($userName, 'bla', 'you fucked up');   
    } 
    /**
     * this will used in getDataSet
     * createMySQLXMLDataSet
     * @return string
     */
    public function getDataSetPath()
    {
        return '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/module/ZfModule/test/data/dataset/moduleswdoctrine.xml';
    }
}
