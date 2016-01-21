<?php

namespace Test;

use Ignaszak\Exception\Controller\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase
{

    private $_controller;

    public function __construct()
    {
        $this->_controller = new Controller;
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
        $renameArrayKeys = Mock\MockTest::callProtectedMethod($this->_controller, 'renameArrayKeys', array($args));
        $this->assertEquals($array, $renameArrayKeys);
    }

    public function testIsArrayNotEmpty()
    {
        $args = array('','',1,'');
        $isArrayNotEmpty = Mock\MockTest::callProtectedMethod($this->_controller, 'isArrayNotEmpty', array($args));
        $this->assertEquals(1, $isArrayNotEmpty);

        $args = array('','',0,'');
        $isArrayNotEmpty = Mock\MockTest::callProtectedMethod($this->_controller, 'isArrayNotEmpty', array($args));
        $this->assertEquals(0, $isArrayNotEmpty);
    }
}
