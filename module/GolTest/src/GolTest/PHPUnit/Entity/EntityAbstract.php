<?php

namespace GolTest\PHPUnit\Entity;


use Zend\Stdlib\Hydrator\ClassMethods;
abstract class EntityAbstract extends \PHPUnit_Framework_TestCase
{
  
    protected  $_expectedConstructedData = null;
  
    /**
    * $extractedData
    * @var array
    */ 
   protected $extractedData = null;
   /**
    *expected data
    * @var array
    */
    protected $expectedData = null;
   
    protected $filteredExtractedData;
    
    protected $_filteredExpectedConstructedData;

    protected $_entityClass=null;

    protected $_testableEntity=null;
   
    public function testDataLengthEqualPropertyCount()
    {      
       $this->assertExpectedDataLengthEqualPropertyCount($this->getExpectedConstructedData());
    }
   
    /**
     * @todo move to abstract class
     * @param array $data
     */
    public function assertExpectedDataLengthEqualPropertyCount(array $data)
    {
        $message = 'the amount of variables you expect does not match entity property count';
        $actual = count($data);
        $expected = $this->getEntityPropertyCount();
        $this->assertEquals($expected, $actual, $message);
    }
    
    /**
     * @todo put in abstract entity tester class
     */
    public function getEntity()
    {
        $this->_testableEntity = new $this->_entityClass;
      
        return $this->_testableEntity;
    }
    /**
     * @todo put in abstract entity tester class
     */
    public function assertConstructedData()
    {
        $this->extract();
        $this->_assertConstructedData();
    }

    /**
     * @dataProvider dataProviderClassMethods
     * @param array $data
     */
    public function assertClassMethods(array $data)
    {
        $entity = $this->getEntity();
        $this->hydryate($data, $entity); 
        $this->extract($entity);
        $this->assertEqualsExtractedExpected();       
    }
    /**
     * @todo put in abstract entity tester class 
     * @param array $data
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function hydrate($data)
    {
        $this->expectedData = $data;
        $hydrator = new ClassMethods();
        
        $hydrator->hydrate($data, $this->_testableEntity);
       
       return $this;       
    }
    /**
     * @todo put in abstract entity tester class
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function extract()
    {
        $extractor = new ClassMethods();
        $data = $extractor->extract($this->_testableEntity);
        
        $this->extractedData = $data;
       
        return $this;
    }
    /**
     * @todo put in abstract entity tester class
     */
    public function assertEqualsExtractedExpected()
    {
        $message = 'extracted data doesn\'t match expectations';
        $this->assertEquals($this->expectedData, $this->extractedData, $message);
    }
    /**
     * @todo put in abstract entity tester class
     */
    protected function _assertConstructedData()
    {
        $this->prepareAssertConstructedDataTest()
             ->assertConstructedDataFilterObjects()
             ->assertEquals(
                 $this->filteredExtractedData, 
                 $this->_filteredExpectedConstructedData,
                 'constructed data not as expected'
             );
        
        return $this;
    }
    /**
     * assign expected and extracted data to own reference so we can
     * manipulate without destroying info for later tests
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function prepareAssertConstructedDataTest()
    {
        $this->filteredExtractedData = $this->extractedData;
        $this->_filteredExpectedConstructedData = $this->getExpectedConstructedData();
        
        return $this;
    }
    /**
     * loop data to assert. assert and remove objects
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function assertConstructedDataFilterObjects()
    {
        foreach ($this->_filteredExpectedConstructedData as $key => $value) {
            if (is_object($value)) {
                $this->assertConstructedDataObject($key)
                     ->unsetKeyFromAssertConstructedData($key);
            }
        }
        
        return $this;
    }
    public function testPropertiesProtected()
    {
        $message = 'property not protected: '; 
        $ref = $this->getEntityReflection();
        foreach ($ref->getProperties() as $property) {
            $name = $property->getName();
            $bool = $property->isProtected();
            $this->assertTrue($bool, $message.$name);
        }
    }
    public function testFunctionsArePublic()
    {
        $message = 'method not public: '; 
        $ref = $this->getEntityReflection();
        foreach ($ref->getMethods() as $method) {
            $name = $method->getName();
            $bool = $method->isPublic();
            $this->assertTrue($bool, $message.$name);
        }
    }

    /**
     * @dataProvider provider
     */
    public function testSettersReturnSelf($data)
    {
        $message = 'setter has not returned self';
        $entity = $this->getEntity();
        foreach ($data as $key => $value) {
            $property = $this->transformUnderslashToCamelCase($key);
            $method = 'set'.ucfirst($property);
            $actual = $entity->$method($value);
            $this->assertEquals($actual, $entity, $message);
        }
        
    }

    /**
     * assert objects are of expected class then unset
     * @param mixed $key logically must be a string for use as name but ...
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function assertConstructedDataObject($key)
    {
         $message = 'object created during contruction not of expecteds type';
         $expected = $this->_filteredExpectedConstructedData[$key];
         $actual = $this->filteredExtractedData[$key];
         $this->assertObjectsSameType($expected, $actual, $message);
         
         return $this;
    }
    /**
     * 
     * @param object $expected
     * @param object $actual
     * @param string $message
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function assertObjectsSameType($expected, $actual, $message)
    {
        $expectedName = get_class($expected);
        $actualName = get_class($actual);
        $this->assertEquals($actualName, $expectedName, $message);
        
        return $this;
    }
    /**
     * unset asserted objects from further comparison
     * @param mixed $key
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function unsetKeyFromAssertConstructedData($key)
    {
        unset($this->_filteredExpectedConstructedData[$key]);
        unset($this->filteredExtractedData[$key]);
        
        return $this;
    }
   
  
    public function testConstructedData()
    {    
         $this->getEntity();
         $this->assertConstructedData();
    }
    /**
     * assert that we get out what we put in following normal
     * class methods routine
     * @dataProvider provider
     * @param array $data
     * @todo put in abstract entity tester class
     */
    public function testAssertClassMethods($data)
    {
        $entity = $this->getEntity();
        $this->hydrate($data); 
        $this->extract();
        $this->assertProtertiesHaveCorrectNames($entity);
        $this->assertEqualsExtractedExpected();       
    }
    public function transformUnderslashToCamelCase($name)
    {
         $transform = function($letters) {
            $letter = substr(array_shift($letters), 1, 1);
            return ucfirst($letter);
         };
        
        $transformed = preg_replace_callback('/(_[a-z])/', $transform, $name);
        
        return $transformed;
    }
    public function assertProtertiesHaveCorrectNames($entity)
    {
        $transform = function($letters) {
            $letter = substr(array_shift($letters), 1, 1);
            return ucfirst($letter);
        };
        $message = 'incorrect data retrieved from property';
        $entityReflection = new \ReflectionObject($entity);
        foreach ($this->expectedData as $key => $value) {
            $property = preg_replace_callback('/(_[a-z])/', $transform, $key);
            $reflectionProp = is_object($entityReflection->getProperty($property));
            
            $this->assertTrue($reflectionProp, $message);
        }
    }

    /**
     * assert that the object has twice as many methods
     * as properties not counting constructor
     * @return \Zend\Test\PHPUnit\Entity\EntityAbstract
     */
    public function testMethodPropertyRatio()
    {
        $message = 'not correct proportion of properties to methods';
        $methodCount = $this->adjustMethodCount();
        $propertyCount = $this->getEntityPropertyCount();
        $balance = (($methodCount / 2) === $propertyCount);
        $this->assertTrue($balance, $message);
        
        return $this;
    }
    /**
     * entity reflection
     * @return \ReflectionObject
     */
    public function getEntityReflection()
    {
        return new \ReflectionObject($this->getEntity());
    }
    /**
     * count of all entity properties
     * @return int
     */
    public function getEntityPropertyCount()
    {
        $ref = $this->getEntityReflection();
        
        return count($ref->getProperties());
    }
    /**
     * return the number of methods 
     * -1 if has constructor
     * @return int
     */
    public function adjustMethodCount()
    {
        $ref = $this->getEntityReflection();
        $methodCount = count($ref->getMethods());
        if ($ref->getConstructor()) {
            --$methodCount;
        }
        
        return $methodCount;
    }
}
