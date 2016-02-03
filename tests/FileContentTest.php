<?php

namespace Test;

use Ignaszak\Exception\Modules\FileContent;
use Test\Mock\MockTest;

class FileContentTest extends \PHPUnit_Framework_TestCase
{

    private $_fileContent;

    public function setUp()
    {
        $this->_fileContent = new FileContent;
    }

    public function testGetFileFragment()
    {
        $content = "
Line1
Line2
Line3
Error
Line5";
        $file = MockTest::mockFile('FileWithError.php', 0644, $content);
        MockTest::inject($this->_fileContent, 'file', $file);
        MockTest::inject($this->_fileContent, 'line', 4);
        MockTest::inject($this->_fileContent, 'offset', 1);
        $fileFragment = MockTest::callMockMethod(
            $this->_fileContent,
            'getFileFragment'
        );
        $this->assertEquals("Line3
Error
", $fileFragment);
    }

    public function testGetFileContentWithNoEsistingFile()
    {
        $anyLine = 5;
        $this->assertEmpty(
            $this->_fileContent->getFileContent(
                'NoExistingFile',
                $anyLine
            )
        );
    }

    public function testGetFileContentWithNoReadableFile()
    {
        $anyLine = 5;
        $noReadableFile = MockTest::mockFile('file.php', 0000);
        $this->assertEmpty(
            $this->_fileContent->getFileContent(
                $noReadableFile,
                $anyLine
            )
        );
    }

    public function testGetFileContent()
    {
        $anyLine = 5;
        $noReadableFile = MockTest::mockFile('file.php', 0644, "Error");
        $this->assertNotEmpty(
            $this->_fileContent->getFileContent(
                $noReadableFile,
                $anyLine
            )
        );
    }

    public function testBeginWithFirstLine()
    {
        MockTest::inject($this->_fileContent, 'offset', 10);
        MockTest::inject($this->_fileContent, 'line', 5);
        $this->assertEquals(
            0,
            MockTest::callMockMethod($this->_fileContent, 'getBegin')
        );
    }

    public function testBegin()
    {
        MockTest::inject($this->_fileContent, 'offset', 10);
        MockTest::inject($this->_fileContent, 'line', 66);
        $this->assertEquals(
            56,
            MockTest::callMockMethod($this->_fileContent, 'getBegin')
        );
    }
}
