<?php

namespace Test;

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
        $getTrace = Mock\MockTest::callProtectedMethod($this->_handler, 'getTrace');
        $this->assertTrue(!empty($getTrace[0]));
    }

    public function testGetFunctionArgs()
    {
        $args = array(
            1, 'string', new \Exception('test')
        );
        $getFunctionArgs = Mock\MockTest::callProtectedMethod($this->_handler, 'getFunctionArgs', array($args));
        $this->assertEquals("1, 'string', (object) Exception", $getFunctionArgs);
    }

    public function testCutString()
    {
        $string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ultrices enim turpis, in auctor massa dictum sit amet. In hac habitasse platea dictumst.";
        $cutString = Mock\MockTest::callProtectedMethod($this->_handler, 'cutString', array($string));
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetur ...", $cutString);

        $string = "Lorem ipsum dolor sit amet, consectetur";
        $cutString = Mock\MockTest::callProtectedMethod($this->_handler, 'cutString', array($string));
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetur", $cutString);
    }

}
