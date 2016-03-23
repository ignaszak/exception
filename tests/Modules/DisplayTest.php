<?php
namespace Test\Modules;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Modules\Display;
use Test\Mock\MockTest;

class DisplayTest extends \PHPUnit_Framework_TestCase
{

    private $display;

    public function setUp()
    {
        $this->display = new Display;
    }

    /**
     * @expectedException \Exception
     */
    public function testLoadDisplay()
    {
        MockTest::inject($this->display, 'baseDir');
        MockTest::mockConf('display', 'anyFile');
        $file = MockTest::mockFile(
            'anyFile.php',
            0644,
            '<?php throw new \Exception(); ?>'
        );
        $this->display->loadDisplay($file);
    }

    public function testDisableLoadDisplay()
    {
        MockTest::inject($this->display, 'baseDir');
        MockTest::mockConf('display', '');
        $file = MockTest::mockFile(
            'anyFile.php',
            0644,
            '<?php throw new \Exception(); ?>'
        );
        $this->display->loadDisplay($file);
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadLocation()
    {
        if (!defined('TEST_MODE')) {
            define('TEST_MODE', true);
        }
        $location = 'http://example.com';

        $_conf = Conf::instance();
        $_conf->setProperty('userLocation', $location);
        $_conf->setProperty('display', 'location');

        $this->display->loadLocation();

        $headers_list = xdebug_get_headers();
        $this->assertContains("Location: $location", $headers_list);
    }

    public function testGetErrorArray()
    {
        MockTest::injectStatic(
            'Ignaszak\Exception\Controller\IController',
            'errorArray',
            array(true)
        );
        $this->assertTrue(
            MockTest::callMockMethod($this->display, 'getErrorArray')[0]
        );
    }

    public function testGetServerData()
    {
        $this->assertNotEmpty(
            MockTest::callMockMethod($this->display, 'getServerData')
        );
    }

    public function testGetUserMessage()
    {
        $message = "Any string";
        MockTest::mockConf('userMessage', $message);
        $this->assertEquals(
            $message,
            MockTest::callMockMethod($this->display, 'getUserMessage')
        );
    }

    public function testGetInf()
    {
        $this->assertEquals(
            date('G:i:s', time()),
            $this->getInf('time')
        );
        $path = 'path/to/logs';
        MockTest::mockConf('logFileDir', $path);
        $this->assertEquals(
            $path,
            $this->getInf('path')
        );
        MockTest::mockConf('createLogFile', false);
        $this->assertEquals(
            'no',
            $this->getInf('log')
        );
        MockTest::mockConf('createLogFile', true);
        $this->assertEquals(
            'yes',
            $this->getInf('log')
        );
        $this->assertEmpty($this->getInf('noMatched'));
    }

    public function testDontLoadFile()
    {
        $file = MockTest::mockFile(
            'AnyThemeFile.php',
            0777,
            '<?php new \Test\Modules\TestLoadCount; ?>'
        );
        MockTest::callMockMethod($this->display, 'load', array($file));
        $this->assertEquals(0, TestLoadCount::$count);
    }

    public function testLoadFile()
    {
        MockTest::inject($this->display, 'baseDir'); // Clear baseDir for mock file
        $file = MockTest::mockFile(
            'AnyThemeFile.php',
            0777,
            '<?php new \Test\Modules\TestLoadCount; ?>'
        );
        MockTest::callMockMethod($this->display, 'load', array($file));
        $this->assertEquals(1, TestLoadCount::$count);
    }

    private function getInf(string $inf): string
    {
        return MockTest::callMockMethod($this->display, 'getInf', array($inf));
    }
}

class TestLoadCount
{
    public static $count = 0;
    public function __construct()
    {
        ++self::$count;
    }
}
