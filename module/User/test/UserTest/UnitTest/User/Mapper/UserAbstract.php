<?php
namespace UserTest\UnitTest\User\Mapper;
use GolTest\PHPUnit\Mapper\MapperAbstract;
use User\Mapper\User;

abstract class UserAbstract extends MapperAbstract
{
   
    public function getMapper()
    {
        $mockEM = $this->getMockEM();

        $mockOptions = $this->getMockOptions();
        $mapper = new User($mockEM, $mockOptions);
        $this->assertMapperHasCorrectDepencies($mapper);
        
        return $mapper;
    }
    public function prepareEmForGetRepository($mockEm, $mockRep, $userEntityClass)
    {
        $mockEm->expects($this->once())
               ->method('getRepository')
               ->with($userEntityClass)
               ->will($this->returnValue($mockRep));
        
        return $mockEm;
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
    
    public function assertMapperHasCorrectDepencies( User $mapper)
    {
         $mockOptions = $mapper->getOptions();
         $mockEm = $mapper->getEntityManager();
         
         $optionsAsExpected = ($mockOptions instanceof \ZfcUserDoctrineORM\Options\ModuleOptions);
     
         $this->assertTrue($optionsAsExpected, 'options object not correct type');
   
         $this->assertEmIsCorrectType($mockEm);
    }
    public function prepareEmForGetBaseQueryBuilder($mockEm, $mockQb)
    {
        $mockEm = $mockEm->expects($this->once())
                         ->method('createQueryBuilder')
                         ->will($this->returnValue($mockQb));
                 
        return $mockEm;
    }
    public function prepareQbForGetBaseQueryBuilder($mockQb)
    {
         $mockQb->expects($this->exactly(2))
               ->method('add')
               ->will($this->returnSelf());
        
         return $mockQb;
    }
    
}
