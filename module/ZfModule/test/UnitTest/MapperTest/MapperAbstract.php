<?php

abstract class MapperAbstract extends PHPUnit_Framework_TestCase
{
    
    /**
     * require_once __dir__.'/Mock.php';
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        require_once __dir__.'/Mock.php';
        parent::__construct($name, $data, $dataName);
    }
    
    public function getMockRepostitory()
    {
        $mockb = $this->getMockBuilder('Mock_Doctrine_ORM_EntityRepository');
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
        $mockB = $this->getMockBuilder('Mock_Doctrine_Query');
        $mockB->disableOriginalConstructor();
       
        return $mockB->getMock();
    }
    
    public function assertEmIsCorrectType($mockEm)
    {   
        $message = 'not Doctrine\ORM\EntityManager as expected';
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $mockEm, $message);
    }
    
}
