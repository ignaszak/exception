<?php

namespace Test;

use Ignaszak\Exception\Modules\FileContent;
use Test\Mock\MockTest;

class FileContentTest extends \PHPUnit_Framework_TestCase
{

    private $_fileContent;

    public function __construct()
    {
        $this->_fileContent = new FileContent;
        MockTest::callMockMethod($this->_fileContent, 'setArgs', array(__FILE__, 16));
    }

    public function testLoadFileToArray()
    {
        MockTest::callMockMethod($this->_fileContent, 'loadFileToArray');
        $fileArray = \PHPUnit_Framework_Assert::readAttribute($this->_fileContent, 'fileArray');

        $this->assertEquals(26, count($fileArray));
    }
}
