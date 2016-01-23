<?php
namespace Test;

use Ignaszak\Exception\Start;
use Ignaszak\Exception\Conf;
use Test\Mock\MockTest;

class StartTest extends \PHPUnit_Framework_TestCase
{

    private $start;

    public function setUp()
    {
        $this->start = new Start;
    }

    public function testConstructor()
    {
        $_errorHandler = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            '_errorHandler'
        );
        $this->assertInstanceOf(
            'Ignaszak\Exception\Handler\ErrorHandler',
            $_errorHandler
        );

        $_exceptionHandler = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            '_exceptionHandler'
        );
        $this->assertInstanceOf(
            'Ignaszak\Exception\Handler\ExceptionHandler',
            $_exceptionHandler
        );

        $_conf = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            '_conf'
        );
        $this->assertInstanceOf(
            'Ignaszak\Exception\Conf',
            $_conf
        );
    }

    public function testCatchException()
    {
        $stub = $this->getMockBuilder('Ignaszak\Exception\Handler\ExceptionHandler')
            ->getMock();
        $stub->expects($this->once())
            ->method('catchException');
        MockTest::inject($this->start, '_exceptionHandler', $stub);
        $this->start->catchException(new \Exception('Any Exception'));
    }

    public function testRunOnDevAndUserMode()
    {
        $stub = $this->getMockBuilder('Ignaszak\Exception\Handler\ErrorHandler')
            ->getMock();
        $stub->expects($this->once())->method('setErrorHandler');
        $stub->expects($this->once())->method('setShutdownHandler');
        MockTest::inject($this->start, '_errorHandler', $stub);

        $stub = $this->getMockBuilder('Ignaszak\Exception\Handler\ExceptionHandler')
            ->getMock();
        $stub->expects($this->once())->method('setExceptionHandler');
        MockTest::inject($this->start, '_exceptionHandler', $stub);

        $this->start->display = 'user';
        $this->start->run();
    }
}
