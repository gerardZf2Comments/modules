<?php

use User\Mapper\User;
require_once __dir__.'/UserAbstract.php';

class FindByEmailTest extends UserAbstract
{
    /**
     *email
     * @var string
     */
    protected $_email=null;
    /**
     * get a mapper with mocks that expect and return the right things
     * @return User\Mapper\User 
     */
    public function getMapper()
    {
        $mapper = parent::getMapper();
              
        $mockOptions = $mapper->getOptions();
        $mockEm = $mapper->getEntityManager();
        
        $this->prepareMockOptions($mockOptions);
        $this->prepareMockEm($mockEm);
        
        return $mapper;
    }
    /**
     * expects once method('findOneBy') ExpectedParams
     * @param mock $mockEm
     * @return mock
     */
    public function prepareMockEm($mockEm)
    {   
        $mockRep = $this->getMockRepostitory();
        $mockRep->expects($this->once())
                ->method('findOneBy')
                ->with($this->getRepositryExpectedParams());
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $this->prepareEmForGetRepository($mockEm, $mockRep, 'User\Entity\User');
         
        return $mockEm;                                   
    }
    public function getRepositryExpectedParams()
    {
         $params = array('email'=> $this->_email);
        
        return $params;
    }

    public function prepareMockOptions($mockOptions)
    {   
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockOptions = $mockOptions->expects($this->once())
                                   ->method('getUserEntityClass')
                                   ->will($this->returnValue('User\Entity\User'));
        
        return $mockOptions;                                   
    }
    
    
    /**
     * data provider
     * @return array
     */
    public function dataProvider()
    {
        $data = array(
           array(true),
        );
        return $data;
    }
    /**
     * @dataProvider dataProvider
     * @param int $limit
     * @param string $sort
     * @param string $orderBy
     */
    public function test($email)
    {
        $this->_email = $email;
        
        $mapper = $this->getMapper();
        
        $result = $mapper->findByEmail($email);
        
        $this->assertTrue(true, $result, 'result not as mocked');
    }   
}
