<?php

namespace Test;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Handler\ErrorHandler;
use Ignaszak\Exception\Handler\Module\LogFile;

class LogFileTest extends \PHPUnit_Framework_TestCase
{

    private $_logFile;
    private $_errorHandler;
    private $_conf;
    private $logFileArray = array();

    public function __construct()
    {
        $this->_logFile = new LogFile;
        $this->_conf = Conf::instance();
        $this->_conf->setProperty('createLogFile', true);
        $this->_conf->setProperty('logFileDir', dirname(__DIR__) . '/logs');
        $this->_errorHandler = new ErrorHandler;
        $this->_errorHandler->setErrorHandler();
        trigger_error('Test LogFile');
        $this->_logFile->createLogFile();
        $this->_logFile->createLogFileListArray();
    }

    public function testlogFileArray()
    {
        $this->logFileArray = $this->_logFile->getLogFileArray();
        $this->assertTrue(!empty($this->logFileArray));
    }

    public function testCreateLogFile()
    {
        $file = $this->_conf->get('logFileDir') . '/' . $this->logFileArray[0]['fileName'];
        $this->assertTrue(file_exists($file));
    }

}
