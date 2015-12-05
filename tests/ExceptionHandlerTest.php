<?php

namespace Test;

use Ignaszak\Exception\Handler\ExceptionHandler;
use Ignaszak\Exception\Handler\IController;

class ExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{

    private $_exceptionHandler;

    public function setUp()
    {
        $this->_exceptionHandler = new ExceptionHandler;
    }

    public function testCatchException()
    {
        $message = 'Test catchException()';
        try {
            throw new \Exception($message);
        } catch(\Exception $e) {
            $this->_exceptionHandler->catchException($e);
        } finally {
            $errorArray = IController::getErrorArray();
            $errorIndex = count($errorArray) - 1;
            $errorArray = $errorArray[$errorIndex];
            $this->assertEquals($errorArray['message'], $message);
        }
    }

}
