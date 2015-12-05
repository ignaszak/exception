<?php

namespace Test;

use Ignaszak\Exception\Handler\ErrorHandler;
use Ignaszak\Exception\Handler\IController;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{

    private $_errorHandler;

    public function setUp()
    {
        $this->_errorHandler = new ErrorHandler;
    }

    public function testSetErrorHandler()
    {
        $this->_errorHandler->setErrorHandler();
        $message = 'Test setErrorHandler()';
        trigger_error($message);

        $errorArray = $this->getErrorArray();

        $this->assertEquals($errorArray['message'], $message);
        $this->assertEquals($errorArray['file'], __FILE__);
    }

    public function testSetShutdownHandler()
    {
        $this->_errorHandler->setErrorHandler();
        $message = 'Test setShutdownHandler()';
        trigger_error($message, E_USER_ERROR);

        $errorArray = $this->getErrorArray();

        $this->assertEquals($errorArray['message'], $message);
        $this->assertEquals($errorArray['file'], __FILE__);
    }

    public function testGetErrorTypeByNumber()
    {
        $errorType = E_COMPILE_WARNING;
        $getErrorTypeByNumber = Mock\MockTest::callProtectedMethod($this->_errorHandler, 'getErrorTypeByNumber', array($errorType));
        $this->assertEquals($getErrorTypeByNumber, 'Compile warning');
    }

    private function getErrorArray()
    {
        $errorArray = IController::getErrorArray();
        $errorIndex = count($errorArray) - 1;
        return $errorArray[$errorIndex];
    }

}
