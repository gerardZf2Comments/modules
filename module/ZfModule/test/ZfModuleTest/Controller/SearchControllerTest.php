<?php

namespace ZfModuleTest\Controller;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
/**
 * 
 *
 * @author gerard
 */
class SearchControllerTest extends AbstractHttpControllerTestCase{
    //put your code here

public function setUp()
{
$this->setApplicationConfig(
include __dir__.'/../../../../../config/application.config.php'
);
parent::setUp();
}
public function testIndexActionCanBeAccessed()
{
$this->dispatch('/');
$this->assertResponseStatusCode(200);
$this->assertModuleName('Album');
$this->assertControllerName('Album\Controller\Album');
$this->assertControllerClass('AlbumController');
$this->assertMatchedRouteName('album');
}
}

?>
