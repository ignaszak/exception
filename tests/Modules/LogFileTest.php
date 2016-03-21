<?php
namespace Test\Modules;

use Ignaszak\Exception\Modules\LogFile;
use Test\Mock\MockTest;

class LogFileTest extends \PHPUnit_Framework_TestCase
{

    private $_logFile;

    public function setUp()
    {
        $this->_logFile = new LogFile();
    }

    public function testLogDirIsNotWritable()
    {
        $this->dirChmod(0444);
        $this->_logFile = new LogFile();
        $this->assertFalse($this->_logFile->createLogFile());
    }

    public function testCreateLogISDisabled()
    {
        $this->dirChmod(0777);
        MockTest::mockConf('createLogFile', false);
        $this->_logFile = new LogFile();
        $this->assertFalse($this->_logFile->createLogFile());
    }

    public function testCreateLogFile()
    {
        $this->dirChmod(0777);
        $this->_logFile = new LogFile();
        $this->assertTrue($this->_logFile->createLogFile());
    }



    private function dirChmod($mode)
    {
        $dir = MockTest::mockDir('anyDir');
        chmod($dir, $mode);
        MockTest::mockConf('logFileDir', $dir);
        MockTest::mockConf('createLogFile', true);
    }
}
