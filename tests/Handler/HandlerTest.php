<?php
namespace Test\Handler;

use Test\Mock\MockTest;

class HandlerTest extends \PHPUnit_Framework_TestCase
{

    private $_handler;

    public function __construct()
    {
        $this->_handler = $this->getMockForAbstractClass('Ignaszak\Exception\Handler\Handler');
        @trigger_error('Test getTrace()');
    }

    public function testGetTrace()
    {
        $getTrace = MockTest::callMockMethod($this->_handler, 'getTrace');
        $this->assertTrue(!empty($getTrace[1]));
    }

    public function testGetFunctionArgsInArray()
    {
        $args = array(
            1, 'string', new \Exception('test')
        );
        $getFunctionArgs = MockTest::callMockMethod($this->_handler, 'getFunctionArgs', array($args));
        $this->assertEquals("1, 'string', (object) Exception", $getFunctionArgs);
    }

    public function testGetFunctionArgsInString()
    {
        $args = 'anyArg';
        $getFunctionArgs = MockTest::callMockMethod($this->_handler, 'getFunctionArgs', array($args));
        $this->assertEquals("'anyArg'", $getFunctionArgs);
    }

    public function testCutString()
    {
        $string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ultrices enim turpis, in auctor massa dictum sit amet. In hac habitasse platea dictumst.";
        $cutString = MockTest::callMockMethod($this->_handler, 'cutString', array($string));
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetur ...", $cutString);

        $string = "Lorem ipsum dolor sit amet, consectetur";
        $cutString = MockTest::callMockMethod($this->_handler, 'cutString', array($string));
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetur", $cutString);
    }
}
