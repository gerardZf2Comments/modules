<?php

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Description of ControllerTest
 *
 * @author gerard
 */
class CommentControllerTest extends AbstractControllerTestCase {

    /**
     * include config
     * @todo este y eso
     */
    public function setUp()
    {
        $this->setApplicationConfig(
                include '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/config/application.config.php'
        );
        parent::setUp();
    }
    /**
     * return a view model with terminal set to true
     * @return \Zend\View\Model\ViewModel
     */
    public function getTerminalViewModel() {
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('zf-module/tmp/test');
        $viewModel->setTerminal(true);

        return $viewModel;
    }
    /**
     * array of good uri's
     * @return array
     */
    public function addReplyGoodData()
    {
        return array(
            array(
               '/comment/add-reply',              
            ),
        );
    }

    /**
     * @dataProvider addReplyGoodData
     */
    public function testAddReplyUriGoodData($uri) {

        $_SERVER = $this->serverArray();
        $_SERVER['REQUEST_URI'] = $uri; 
        Zend\Console\Console::overrideIsConsole(false);
        $this->getApplication()->run();
         
        $doc = new DOMDocument;
        $doc->loadHTML($this->getApplication()->getResponse()->getContent());
        $form = $doc->getElementsByTagName('form');
        $this->assertEquals($form->length, 1);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('ZfModule');
        $this->assertControllerName('ZfModule\Controller\Comment');
        $this->assertControllerClass('CommentController');

        $this->assertTrue(true);
    }
     /**
     * array of bad uri's
     * @return array
     */
    public function addReplyBadData()
    {
        return array(
            array(
               '/comment/add-reply/',              
            ),
        );
    }

    /**
     * @dataProvider addReplyBadData
     */
    public function testAddReplyUriBadData($uri)
    {
        //var_dump(headers_sent());die();
        $this->reset();
        $_SERVER = $this->serverArray();
        $_SERVER['REQUEST_URI'] = $uri; 
        Zend\Console\Console::overrideIsConsole(false);
        $this->getApplication()->run();
        $this->dispatch($uri);
        $this->assertResponseStatusCode(404);      
    }

    public function serverArray() {
        return array('REDIRECT_APPLICATION_ENV' => 'development', 'REDIRECT_STATUS' => '200', 'APPLICATION_ENV' => 'development', 'HTTP_HOST' => 'moduleswdoctrine', 'HTTP_CONNECTION' => 'keep-alive', 'HTTP_CACHE_CONTROL' => 'max-age=0', 'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.66 Safari/537.36', 'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch', 'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8', 'HTTP_COOKIE' => 'PHPSESSID=jkqkgt0kl6vgbcfo95kjrt40j0', 'PATH' => '/usr/local/bin:/usr/bin:/bin', 'SERVER_SIGNATURE' => '
Apache/2.2.22 (Ubuntu) Server at moduleswdoctrine Port 80
', 'SERVER_SOFTWARE' => 'Apache/2.2.22 (Ubuntu)', 'SERVER_NAME' => 'moduleswdoctrine', 'SERVER_ADDR' => '127.0.0.1', 'SERVER_PORT' => '80', 'REMOTE_ADDR' => '127.0.0.1', 'DOCUMENT_ROOT' => '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/public', 'SERVER_ADMIN' => 'webmaster@localhost', 'SCRIPT_FILENAME' => '/home/gerard/sites/modules.w.doctrine/modules.zendframework.com/public/index.php', 'REMOTE_PORT' => '44352', 'REDIRECT_URL' => '/comment/add-reply', 'GATEWAY_INTERFACE' => 'CGI/1.1', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'REQUEST_METHOD' => 'GET', 'QUERY_STRING' => '', 'REQUEST_URI' => '/comment/add-reply', 'SCRIPT_NAME' => '/index.php', 'PHP_SELF' => '/index.php', 'REQUEST_TIME' => 1381541478,);
    }

}
