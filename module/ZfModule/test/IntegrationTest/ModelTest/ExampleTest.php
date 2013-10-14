<?php

use Zend\Test\PHPUnit\Database;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Mapping\Driver;



/** 
* Description of ControllerTest
 *
 * @author gerard
 */
class ExampleTest extends Database\TestCase
{
    /**
     * 
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '') 
    {
        $this->_baseDir = '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com';
        $this->_testDir = $this->_baseDir . '/module/ZfModule/test';
        $this->_configPath = $this->_baseDir . '/config/application.config.php';
        parent::__construct($name, $data, $dataName);
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
}
