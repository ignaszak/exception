<?php
namespace Test\Modules;

use Ignaszak\Exception\Modules\LogFile;
use Test\Mock\MockTest;

class LogFileTest extends \PHPUnit_Framework_TestCase
{

    private $_logFile;

    public function setUp()
    {
        $this->_logFile = new LogFile();
    }

    public function testLogDirIsNotWritable()
    {
        $this->dirChmod(0444);
        $this->_logFile = new LogFile();
        $this->assertFalse($this->_logFile->createLogFile());
    }

    public function testCreateLogIsDisabled()
    {
        $this->dirChmod(0777);
        MockTest::mockConf('createLogFile', false);
        $this->_logFile = new LogFile();
        $this->assertFalse($this->_logFile->createLogFile());
    }

    public function testCreateLogFile()
    {
        $this->dirChmod(0777);
        $this->_logFile = new LogFile();
        $this->assertTrue($this->_logFile->createLogFile());
    }

    public function testLoadLogDisplayWithoutBacktrace()
    {
        MockTest::injectStatic(
            'Ignaszak\Exception\Controller\IController',
            'errorArray',
            [
                0 => [
                    'type' => 'Warning',
                    'message' => 'anyMessage',
                    'file' => 'anyFile',
                    'line' => 1,
                ]
            ]
        );
        $result = MockTest::callMockMethod($this->_logFile, 'loadLogDisplay');
        $this->assertEquals(
            [
                'errors' => <<<EOT
#1 [Warning]
    anyMessage in anyFile on line 1


EOT
                ,
                'errorsCount' => 1

            ],
            $result
        );
        MockTest::injectStatic(
            'Ignaszak\Exception\Controller\IController',
            'errorArray',
            []
        );
    }

    public function testLoadLogDisplayWithBacktrace()
    {
        MockTest::injectStatic(
            'Ignaszak\Exception\Controller\IController',
            'errorArray',
            [
                0 => [
                    'type' => 'Warning',
                    'message' => 'anyMessage',
                    'file' => 'anyFile',
                    'line' => 1,
                    'trace' => [
                        0 => [
                            'message' => 'backtraceMEssage',
                            'file' => 'backtraceFile',
                            'arguments' => 'anyArguments'
                        ]
                    ]
                ]
            ]
        );
        $result = MockTest::callMockMethod($this->_logFile, 'loadLogDisplay');
        $this->assertEquals(
            [
                'errors' => <<<EOT
#1 [Warning]
    anyMessage in anyFile on line 1
    [Backtrace]:
        backtraceMEssage
              IN: backtraceFile
              ARGUMENTS:
                  anyArguments


EOT
            ,
            'errorsCount' => 1

            ],
            $result
        );
        MockTest::injectStatic(
            'Ignaszak\Exception\Controller\IController',
            'errorArray',
            []
        );
    }

    private function dirChmod($mode)
    {
        $dir = MockTest::mockDir('anyDir');
        chmod($dir, $mode);
        MockTest::mockConf('logFileDir', $dir);
        MockTest::mockConf('createLogFile', true);
    }
}
