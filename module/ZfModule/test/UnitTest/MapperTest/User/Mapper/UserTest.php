<?php
require_once __dir__.'/Mock.php';
class UnitTestUserTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }

    public function getUserMapper()
    {
        $mockEM = $this->getMockEM();

        $mockOptions = $this->getMockOptions();
        
        return new \User\Mapper\User($mockEM, $mockOptions);
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
     * @todo this throws an exception if instanciated with 'Doctrine/ORM/QueryBuilder'
     * @todo check if it's related to finalness
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
     */
    public function getMockQuery()
    {
        $mockB = $this->getMockBuilder('Mock_Doctrine_Query');
        $mockB->disableOriginalConstructor();
       
        return $mockB->getMock();
    }
    /**
     * get a mapper with mocks that expect and return the right things
     * @return User\Mapper\User 
     */
    public function getMapperForFindAll()
    {
        $mapper = $this->getUserMapper();
        $this->assertMapperHasCorrectDepencies($mapper);
        $mockOptions = $mapper->getOptions();
        $mockEm = $mapper->getEntityManager();
        $mockOptions = $this->prepareMockOptionsForFindAll($mockOptions);
        $mockEm = $this->prepareMockEmForFindAll($mockEm);
        
        return $mapper;
    }
    public function prepareMockOptionsForFindAll($mockOptions)
    {   
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockOptions = $mockOptions->expects($this->once())
                                   ->method('getDocUserEntityClass')
                                   ->will($this->returnValue('User\Entity\User'));
        
        return $mockOptions;                                   
    }
    public function prepareMockEmForFindAll($mockEm)
    {   
        $mockQb = $this->prepareMockQbForFindAll();
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockEm = $mockEm->expects($this->once())
                         ->method('createQueryBuilder')
                         ->will($this->returnValue($mockQb));
        
        return $mockEm;                                   
    }
    public function prepareMockQbForFindAll()
    {   
        $mockQb = $this->getMockQueryBuilder();
        $mockQuery = $this->prepareMockQueryForFindAll();
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
      
        $mockQb->expects($this->exactly(2))
               ->method('add')
               ->will($this->returnSelf());
       
        $mockQb->expects($this->once())
               ->method('getQuery')
               ->will($this->returnValue($mockQuery));
        return $mockQb;                                   
    }
    public function prepareMockQueryForFindAll()
    {
        $mockQuery = $this->getMockQuery();
        $mockQuery->expects($this->once())
                  ->method('getResult')
                  ->will($this->returnValue(true));
        
        return $mockQuery;        
    }

    public function getMockQueryForFindAll($expectsSetMaxResults=false)
    {
         /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockQuery();
        if ($expectsSetMaxResults) {
         $this->mockQueryExpectsSetMaxResults($mockQuery);           
        }
        $mockQuery->expects($this->once())
                  ->method('getResult')
                  ->will($this->returnValue(true));
        
        return $mockQuery;        
    }
    public function assertMapperHasCorrectDepencies($mapper)
    {
        $mockOptions = $mapper->getOptions();
        $mockEm = $mapper->getEntityManager();
        $optionsAsExpected = ($mockOptions instanceof \ZfcUserDoctrineORM\Options\ModuleOptions);
        $emAsExpected = ($mockEm instanceof \Doctrine\ORM\EntityManager);
        $this->assertTrue($optionsAsExpected, 'options object not correct type');
        $this->assertTrue($emAsExpected, 'em object not correct type');
    }

    public function testFindAll()
    {
        $mapper = $this->getMapperForFindAll();
        $result = $mapper->findAll();
        $this->assertTrue(true, $result, 'result not as mocked');
    }
    
    
   
}
