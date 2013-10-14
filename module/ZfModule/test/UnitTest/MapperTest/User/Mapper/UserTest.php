<?php
 
use User\Mapper\User;

class UnitTestUserTest extends PHPUnit_Framework_TestCase
{
    public function getUserMapper()
    {
        $mockEM = $this->getMockEM();

        $mockOptions = $this->getMockOptions();
        
        return new User($mockEM, $mockOptions);
    }
    /**
     * mock Doctrine\ORM\EntityManager
     * @return mock
     */
    public function getMockOptions()
    {
        $mockB = $this->getMockBuilder('ZfcUserDoctrineORM\Options\ModuleOptions');
        
        return $mockB->getMock();
    }
    /**
     * mock 'Doctrine\ORM\EntityManager'
     * @return Mock
     */
    public function getMockEM()
    {
        $mockB = $this->getMockBuilder('Doctrine\ORM\EntityManager');
        $mockB->disableOriginalConstructor();
        
        return $mockB->getMock();
    }
    /**
     * mock Doctrine/ORM/QueryBuilder
     * @return mock
     */
    public function getMockQueryBuilder()
    {
         $mockB =  $this->getMockBuilder('Doctrine/ORM/QueryBuilder');
         $mockB->disableOriginalConstructor();
         
         return $mockB->getMock();
    }
    /**
     * mock Doctrine\ORM\Query
     * @return mock
     */
    public function getMockQuery()
    {
        $mockB = $this->getMockBuilder('Doctrine\ORM\Query');
        $mockB->disableOriginalConstructor();
        
        return $mockB->getMock();
    }
    public function getMapperForFindAll()
    {
        $this->getUserMapper();
    }
    public function testExp()
    {
        var_dump($this->getUserMapper());
    }
}