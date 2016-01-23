<?php

namespace Test;

use Ignaszak\Exception\Handler\ErrorHandler;
use Ignaszak\Exception\Modules\LogFile;
use Test\Mock\MockTest;

class LogFileTest extends \PHPUnit_Framework_TestCase
{

    private $_logFile;
    private $_errorHandler;
    private $logFileArray = array();

    public function setUp()
    {
        $this->_logFile = new LogFile;
        $this->_errorHandler = new ErrorHandler;
        $this->_errorHandler->setErrorHandler();
        trigger_error('Test LogFile');
        $this->_logFile->createLogFile();
    }

    public function testCreateLogFile()
    {
        $logFileDir = dirname(__DIR__) . '/logs';
        MockTest::mockConf('createLogFile', true);
        MockTest::mockConf('logFileDir', $logFileDir);
        $file = "{$logFileDir}/{$this->logFileArray[0]['fileName']}";
        $this->assertTrue(file_exists($file));
    }
}
