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
class UserTest extends \Zend\Test\PHPUnit\Database\TestCase
{
    /**
     * 
     * @param string $name
     * @param array $data
     * @param string $dataname
     */
    public function __construct($name = '', array $data = array(), $dataName = '') 
    {
        $this->_baseDir = '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com';
        $this->_testDir = $this->_baseDir . '/module/ZfModule/test';
        $this->_configPath = $this->_baseDir . '/config/application.config.php';
        parent::__construct($name, $data, $dataName);
    }

        /**
     * this belongs in a abstract class
     * @return \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet
     */
    public function getDataSet()
    {
        $ds = $this->createMySQLXMLDataSet($this->_testDir. '/data/dataset/moduleswdoctrine.xml');
        $rds = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
        $rds->addFullReplacement('##NULL##', null);
       
        return $rds;
    }
    /**
     * this is a working test 
     */
    public function testExample()
    {       
        $em = $this->getEntityManager();
        $entity = $em->find('User\Entity\User', 1);  
        /** @var entity User\Entity\User */
        $this->assertEquals($entity->getUsername(), 'bla', 'username incorrect');
        $this->assertEquals($entity->getEmail(), 'gera@yuu.com', 'email error');
        $this->assertEquals($entity->getDisplayName(), 'blas', 'display name error');
        $this->assertEquals($entity->getPassword(), 'bfhkab', 'passwd');
        $this->assertEquals($entity->getId(), 1, 'id not 1');        
    }
    
    public function testFindByEmailSuccess()
    {
        $entity = $this->findBy('gera@yuu.com');
        $this->assertEquals($entity->getId(), 1, 'id not 1');
        $this->assertEquals($entity->getEmail(), 'gera@yuu.com', 'email error');
    }
    
    public function testFindByEmailFailure()
    {       
        $entity = $this->findBy('gerad@yuu.com');
        $this->assertEquals($entity, null, 'expected null');
    }
    public function findBy($email)
    {
        $mapper = $this->getMapper();
        
        return $entity = $mapper->findByEmail($email);
    }

    public function getMapper()
    {
        $sm = $this->getApplication()->getServiceManager();
        $mapper = $sm->get('zfcuser_user_mapper');
        
        return $mapper;
    }
    public function testFindAll()
    {
        $mapper = $this->getMapper();
        $results = $mapper->findAll();
        $this->assertEquals(6, count($results), 'count isn\'t right');
        
        $firstResult = $results[0];
        $this->assertUserEntityType($firstResult);
        $this->assertEquals(1, $firstResult->getId(), 'first result not in order');
    
        $lastResult = $results[5];
        $this->assertUserEntityType($lastResult);
        $this->assertEquals(6, $lastResult->getId(), 'last result not 5');
    }
    public function assertUserEntityType($entity)
    {
        $isExpected = ($entity instanceof \User\Entity\User);
        $this->assertTrue($isExpected, 'not an instance of  \User\Entity\User');
    }
    
}
