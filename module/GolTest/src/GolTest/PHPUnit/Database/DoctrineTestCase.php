<?php

namespace GolTest\PHPUnit\Database;

use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Mapping\Driver;

/** 
* Description of ControllerTest
 *@todo define namespace exceptions to use
 * @author gerard
 */
abstract class DoctrineTestCase extends \PHPUnit_Extensions_Database_TestCase
{
     
    /**
     * @return array config to boot off
     */
   abstract function getApplicationConfig();
   
    /**
     * this will used in getDataSet
     * createMySQLXMLDataSet
     * @return string
     */
    abstract function getDataSetPath();
    /**
     * method to be used for creating dataset
     * @var string
     */
    protected $_createDataSetCallbackName = null;
    /**
     * default method to be used for creating dataset
     * @var string
     */
    protected $_defaultCreateDataSetCallbackName = 'createMySQLXMLDataSet';
    /**
     * app
     * @var \Zend\Mvc\Application
     */
  protected $_application=null;
   /**
    * @var array
    */
  protected $_applicationConfig=null;
  
    
    /**
     * only instantiate pdo once for test clean-up/fixture load
     * @var PDO 
     */
     static private $pdo = null;
    /**
     * only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     * @var object 
     */
    private $conn = null;
    /**
  * PDO  defined here
  * don't see the need for all this finalness
  * and staticness. it must goo
  * @return PDO
  */   
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
            
                self::$pdo = new \PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
        
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }
   
        return $this->conn;
    }
    /**
     * replace service manager ServiceListener
     *   with Zend\Test\PHPUnit\Mvc\Service\ServiceListenerFactory
     */
    public function modifyAppConfig()
    {
        
        $consoleServiceConfig = array(
            'service_manager' => array(
                'factories' => array(
                    'ServiceListener' => 'Zend\Test\PHPUnit\Mvc\Service\ServiceListenerFactory',
                ),
            ),
        );
        
        $this->_applicationConfig = array_replace_recursive($this->_applicationConfig, $consoleServiceConfig);
    }

    /**
     * Get the application object
     * @return \Zend\Mvc\ApplicationInterface
     */
    public function getApplication()
    {
        if ($this->_application) {
            return $this->_application;
        }
        $this->setApplicationConfig($this->getApplicationConfig());
        
        $this->modifyAppConfig();
              
        $this->_application = Application::init($this->_applicationConfig);

        $this->unsetApplicationEvents();
        
        return $this->_application;
    } 
    public function unsetApplicationEvents()
    {
        $events = $this->_application->getEventManager();
        foreach ($events->getListeners(MvcEvent::EVENT_FINISH) as $listener) {
            $callback = $listener->getCallback();
            if (is_array($callback) && $callback[0] instanceof SendResponseListener) {
                $events->detach($listener);
            }
        }
    }

    /**
     * Set the application config
     * @param  array $applicationConfig
     * @throws Exception
     */
    public function setApplicationConfig($applicationConfig)
    {
        if (null !== $this->_application && null !== $this->_applicationConfig) {
            throw new \Exception(
                'Application config can not be set, the application is already built'
            );
        }

        // do not cache module config on testing environment
        if (isset($applicationConfig['module_listener_options']['config_cache_enabled'])) {
            $applicationConfig['module_listener_options']['config_cache_enabled'] = false;
        }
        $this->_applicationConfig = $applicationConfig;
        return $this;
    }
    /**
     * list($replace , $replacement) = $rds; 
     * this belongs in a abstract class
     * @return \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet
     */
    public function getDataSet($rds = false)
    {        
        $ds = $this->createDataSet();
        
        if ($rds) {
            list($replace , $replacement) = $rds; 
            $rds = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
            $rds->addFullReplacement($replace, $replacement);
        }
        
        $ret = ($rds) ? $rds : $ds;
        
        return $ret;
    } 
    public function createDataSet()
    {
        $dataSetPath = $this->getDataSetPath();
        $cd = $this->getCreateDataSetCallbackName();
        
        return $this->$cd($dataSetPath);      
    }

    public function getCreateDataSetCallbackName()
    {
        $cd = $this->_defaultCreateDataSetCallbackName;
        
        if ($this->_createDataSetCallbackName) {
            $cd = $this->_createDataSetCallbackName;
        }
        
        if (!method_exists($this, $cd)) {
                throw new \Exception('method doesn\'t exist :'. $cd);
        }
        
        return $cd;
    }

    /**
     * abstract this
     * get initialized app and return $em 
     * @return @todo
     */
    public function getEntityManager()
    {
        $app = $this->getApplication();
        $sl = $app->getServiceManager();
        $em = $sl->get($this->getEmServiceLocatorName());
        
        return $em;
    }
    /**
     * you can override this instead of getEntityManager
     * @return string
     */
    public function getEmServiceLocatorName()
    {
        return 'doctrine.entitymanager.orm_default';
    }
}
