<?php

namespace GolTest\PHPUnit\Mapper;

abstract class MapperAbstract extends \PHPUnit_Framework_TestCase
{
    protected $_mockQueryName = 'GolTest\PHPUnit\Mapper\DoctrineOrmQuery';
    protected $_mockErName = 'GolTest\PHPUnit\Mapper\DoctrineOrmEntityRepostitory';
    
    /**
     * name of class to use for mock entity repository
     * override to add methods
     * @return string
     */
    public function getMockErName()
    {
        return $this->_mockErName;
    }
    /**
     * we don't call this
     * @param string $name
     * @return \GolTest\PHPUnit\Mapper\MapperAbstract
     */
    public function setMockErName($name)
    {
        $this->_mockErName = $name;
        
        return $this;
    }
    /**
     * name of class to use for mock query
     * override to add methods
     * @return string
     */
    public function getMockQueryName()
    {
        return $this->_mockQueryName;
    }
    /**
     * we don't call this
     * @param string $name
     * @return \GolTest\PHPUnit\Mapper\MapperAbstract
     */
    public function setMockQueryName($name)
    {
        $this->_mockQueryName = $name;
        
        return $this;
    }
    /**
     * mock 'Doctrine\ORM\EntityRepository'
     * @return Mock
     */
    public function getMockRepostitory()
    {
        $mockb = $this->getMockBuilder($this->getMockErName());
        $mockb->disableOriginalConstructor();
        
        return $mockb->getMock();
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
         $mockB =  $this->getMockBuilder('Doctrine\ORM\QueryBuilder');
         $mockB->disableOriginalConstructor();
         
         return $mockB->getMock();
    }
    /**
     * mock Doctrine\ORM\Query
     * @return mock
     * @todo move to a very abstract class
     */
    public function getMockQuery()
    {
        $mockQueryName = $this->getMockQueryName();
        $mockB = $this->getMockBuilder($mockQueryName);
        $mockB->disableOriginalConstructor();
       
        return $mockB->getMock();
    }
    
    public function assertEmIsCorrectType($mockEm)
    {   
        $message = 'not Doctrine\ORM\EntityManager as expected';
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $mockEm, $message);
    }
    
}
