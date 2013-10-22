<?php
namespace UserTest\UnitTest\User\Mapper;

use User\Mapper\User;

class FindAllTest extends UserAbstract
{
    /**
     *limit
     * @var int
     */
    protected $_limit=null;
    /**
     *sort
     * @var string
     */
    protected $_sort=null;
    /**
     *order by
     * @var string
     */
    protected $_orderBy=null;
   
    public function prepareMockEm($mockEm)
    {   
         $mockQb = $this->prepareMockQb();
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $this->prepareEmForGetBaseQueryBuilder($mockEm, $mockQb);
         
        return $mockEm;                                   
    }
    /**
     * get a mapper with mocks that expect and return the right things
     * @return User\Mapper\User 
     */
    public function getMapper($limit = null, $sort=null, $orderBy=null)
    {
        $mapper = parent::getMapper();
        
        $mockOptions = $mapper->getOptions();
        $mockEm = $mapper->getEntityManager();
        $mockOptions = $this->prepareMockOptions($mockOptions);
        $mockEm = $this->prepareMockEm($mockEm);
        
        return $mapper;
    }
    public function prepareMockOptions($mockOptions)
    {   
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockOptions = $mockOptions->expects($this->once())
                                   ->method('getUserEntityClass')
                                   ->will($this->returnValue('User\Entity\User'));
        
        return $mockOptions;                                   
    }
    
    public function prepareMockQb()
    {   
        $mockQb = $this->getMockQueryBuilder();
        $mockQuery = $this->prepareMockQuery();
        /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
      
        $this->prepareQbForGetBaseQueryBuilder($mockQb);
       
        $mockQb->expects($this->once())
               ->method('getQuery')
               ->will($this->returnValue($mockQuery));
        
        if ($this->_orderBy === null) {
            return $mockQb;  
        }
        
        $mockQb->expects($this->once())
               ->method('orderBy');
        
        return $mockQb;
    }
    
    /**
     * query for find all
     * expected method adjust for limit being set or not
     * @return mock
     * @todo fix this
     */
    public function prepareMockQuery()
    {
         /**@var $mockOptions PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockQuery();
        
        $mockQuery->expects($this->once())
                  ->method('getResult')
                  ->will($this->returnValue(true));
        
        if ($this->_limit === null) {
            
            return $mockQuery;
        }
        
        $mockQuery->expects($this->once())
                  ->method('setMaxResults')
                  ->with($this->_limit)
                  ->will($this->returnSelf());
        
        return $mockQuery;        
    }
    
    /**
     * data provider
     * @return array
     */
    public function dataProvider()
    {
        $data = array(
            array(null, null, null,),
            array(3, 'r', 'asc',),
        );
        return $data;
    }
    /**
     * @dataProvider dataProvider
     * @param int $limit
     * @param string $sort
     * @param string $orderBy
     */
    public function test($limit = null, $sort=null, $orderBy=null)
    {
        $this->_limit=$limit;
        $this->_sort=$sort;
        $this->_orderBy=$orderBy;
        $mapper = $this->getMapper();
        
        $result = $mapper->findAll($limit, $sort, $orderBy);
        $this->assertTrue(true, $result, 'result not as mocked');
    }   
}
