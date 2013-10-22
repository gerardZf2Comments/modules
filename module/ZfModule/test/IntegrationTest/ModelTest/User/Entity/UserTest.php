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
class UserTest extends DoctrineTestCase
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
     * this will used in getDataSet
     * createMySQLXMLDataSet
     * @return string
     */
    public function getDataSetPath()
    {
        return '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/module/ZfModule/test/data/dataset/moduleswdoctrine.xml';
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
        $this->assertEntityType($entity);
    }
    public function assertEntityType($entity)
    {
        $this->assertInstanceOf('User\Entity\User', $entity, 'entity not of type User\Entity\User');
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
