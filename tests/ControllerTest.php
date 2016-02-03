<?php

namespace Test;

use Ignaszak\Exception\Controller\Controller;
use Test\Mock\MockTest;

class ControllerTest extends \PHPUnit_Framework_TestCase
{

    private $_controller;

    public function setUp()
    {
        $this->_controller = Controller::instance();
    }

    public function testConstructor()
    {
        $_display = \PHPUnit_Framework_Assert::readAttribute(
            $this->_controller,
            '_display'
        );
        $this->assertInstanceOf(
            'Ignaszak\Exception\Modules\Display',
            $_display
        );

        $_logFile = \PHPUnit_Framework_Assert::readAttribute(
            $this->_controller,
            '_logFile'
        );
        $this->assertInstanceOf(
            'Ignaszak\Exception\Modules\LogFile',
            $_logFile
        );
    }

    public function testRenameArrayKeys()
    {
        $array = array(
            'type'     => 1,
            'message'  => 2,
            'file'     => 3,
            'line'     => 4,
            'content'  => 5,
            'trace'    => 6,
            'previous' => 7,
            'code'     => 8
        );
        $args = array(1,2,3,4,5,6,7,8);
        $renameArrayKeys = Mock\MockTest::callMockMethod($this->_controller, 'renameArrayKeys', array($args));
        $this->assertEquals($array, $renameArrayKeys);
    }

    public function testIsArrayNotEmpty()
    {
        $args = array('','',1,'');
        $isArrayNotEmpty = Mock\MockTest::callMockMethod($this->_controller, 'isArrayNotEmpty', array($args));
        $this->assertEquals(1, $isArrayNotEmpty);

        $args = array('','',0,'');
        $isArrayNotEmpty = Mock\MockTest::callMockMethod($this->_controller, 'isArrayNotEmpty', array($args));
        $this->assertEquals(0, $isArrayNotEmpty);
    }

    public function testIsUserReporting()
    {
        MockTest::injectStatic($this->_controller, 'reportedErrorCode', E_NOTICE);
        MockTest::mockConf('userReporting', E_ALL);
        $this->assertTrue(
            MockTest::callMockMethod($this->_controller, 'isUserReporting')
        );
    }

    public function testIsUserNotReporting()
    {
        MockTest::injectStatic($this->_controller, 'reportedErrorCode', E_NOTICE);
        MockTest::mockConf('userReporting', E_ALL & ~E_NOTICE);
        $this->assertFalse(
            MockTest::callMockMethod($this->_controller, 'isUserReporting')
        );
    }

    public function testCallDestructor()
    {
        // Add eror to errorArray
        MockTest::injectStatic($this->_controller, 'errorArray', ['AnyError']);
        MockTest::inject(
            $this->_controller,
            '_logFile',
            $this->mockLogFile("once")
        );
        $this->_controller->__destruct();
    }

    public function testDontCallDestructorWhenDestructorWasCalledBefore()
    {
        MockTest::inject(
            $this->_controller,
            '_logFile',
            $this->mockLogFile("never")
        );
        $this->_controller->__destruct();
    }

    public function testLoadUserMessageWhenUserIsNotReporting()
    {
        // Clear counter to call again destructor
        MockTest::injectStatic($this->_controller, 'count');
        MockTest::inject(
            $this->_controller,
            '_logFile',
            $this->mockLogFile("once")
        );
        MockTest::inject(
            $this->_controller,
            '_display',
            $this->mockDisplay(1)
        );
        $this->_controller->__destruct();
    }

    public function testLoadUserMessageWhenUserIsReporting()
    {
        // Clear counter to call again destructor
        MockTest::injectStatic($this->_controller, 'count');
        MockTest::mockConf('userReporting', E_ALL);
        MockTest::inject(
            $this->_controller,
            '_logFile',
            $this->mockLogFile("once")
        );
        MockTest::inject(
            $this->_controller,
            '_display',
            $this->mockDisplay(2)
        );
        $this->_controller->__destruct();
    }

    private function mockLogFile(string $call)
    {
        $stub = $this->getMockBuilder('Ignaszak\Exception\Modules\LogFile')
            ->getMock();
        $stub->expects($this->$call())->method('createLogFile');
        return $stub;
    }

    private function mockDisplay(int $countCalls)
    {
        $stub = $this->getMockBuilder('Ignaszak\Exception\Modules\Display')
        ->getMock();
        $stub->expects($this->exactly($countCalls))->method('loadDisplay');
        $stub->method('loadLocation');
        return $stub;
    }
}
