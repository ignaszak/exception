<?php

namespace Test;

use Ignaszak\Exception\Handler\ErrorHandler;
use Ignaszak\Exception\Controller\IController;
use Test\Mock\MockTest;

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
        $this->assertEmpty(
            $this->getErrorTypeByNumber(0)
        );
        $this->assertEquals(
            'Fatal error',
            $this->getErrorTypeByNumber(E_ERROR)
        );
        $this->assertEquals(
            'Warning',
            $this->getErrorTypeByNumber(E_WARNING)
        );
        $this->assertEquals(
            'Parse error',
            $this->getErrorTypeByNumber(E_PARSE)
        );
        $this->assertEquals(
            'Notice',
            $this->getErrorTypeByNumber(E_NOTICE)
        );
        $this->assertEquals(
            'Core error',
            $this->getErrorTypeByNumber(E_CORE_ERROR)
        );
        $this->assertEquals(
            'Core warning',
            $this->getErrorTypeByNumber(E_CORE_WARNING)
        );
        $this->assertEquals(
            'Compile error',
            $this->getErrorTypeByNumber(E_COMPILE_ERROR)
        );
        $this->assertEquals(
            'Compile warning',
            $this->getErrorTypeByNumber(E_COMPILE_WARNING)
        );
        $this->assertEquals(
            'User error',
            $this->getErrorTypeByNumber(E_USER_ERROR)
        );
        $this->assertEquals(
            'User warning',
            $this->getErrorTypeByNumber(E_USER_WARNING)
        );
        $this->assertEquals(
            'User notice',
            $this->getErrorTypeByNumber(E_USER_NOTICE)
        );
        $this->assertEquals(
            'Strict notice',
            $this->getErrorTypeByNumber(E_STRICT)
        );
        $this->assertEquals(
            'Recoverable error',
            $this->getErrorTypeByNumber(E_RECOVERABLE_ERROR)
        );
        $this->assertEquals(
            'Deprecated error',
            $this->getErrorTypeByNumber(E_DEPRECATED)
        );
        $this->assertEquals(
            'User deprecated error',
            $this->getErrorTypeByNumber(E_USER_DEPRECATED)
        );
        $this->assertEquals(
            'Recoverable error',
            $this->getErrorTypeByNumber(E_RECOVERABLE_ERROR)
        );
        $this->assertRegExp(
            '/Unknown error \([0-9]*\)/',
            $this->getErrorTypeByNumber(722956451465)
        );
    }

    private function getErrorArray()
    {
        $errorArray = IController::getErrorArray();
        $errorIndex = count($errorArray) - 1;
        return $errorArray[$errorIndex];
    }

    private function getErrorTypeByNumber(int $errorType): string
    {
        return Mock\MockTest::callMockMethod(
            $this->_errorHandler,
            'getErrorTypeByNumber',
            array($errorType)
        );
    }
}
