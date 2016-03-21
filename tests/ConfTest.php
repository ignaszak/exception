<?php
namespace Test;

use Ignaszak\Exception\Conf;
use Test\Mock\MockTest;

class ConfTest extends \PHPUnit_Framework_TestCase
{

    private $_conf;

    public function setUp()
    {
        $this->_conf = Conf::instance();
    }

    public function tearDown()
    {
        MockTest::inject(Conf::instance(), '_conf');
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'Ignaszak\Exception\Conf',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->_conf,
                '_conf'
            )
        );
    }

    public function testProperties()
    {
        $this->_conf->setProperty('errorReporting', E_ALL);
        $this->assertEquals(
            E_ALL,
            $this->_conf->get('errorReporting')
        );

        $this->_conf->setProperty('display', 'dev');
        $this->assertEquals(
            'dev',
            $this->_conf->get('display')
        );

        $this->_conf->setProperty('userReporting', E_ALL);
        $this->assertEquals(
            E_ALL,
            $this->_conf->get('userReporting')
        );

        $this->_conf->setProperty('userMessage', 'anyMessage');
        $this->assertEquals(
            'anyMessage',
            $this->_conf->get('userMessage')
        );

        $this->_conf->setProperty('userLocation', 'example.location.com');
        $this->assertEquals(
            'example.location.com',
            $this->_conf->get('userLocation')
        );

        $this->_conf->setProperty('createLogFile', true);
        $this->assertEquals(
            true,
            $this->_conf->get('createLogFile')
        );

        $this->_conf->setProperty('logFileDir', 'anyDir');
        $this->assertEquals(
            'anyDir',
            $this->_conf->get('logFileDir')
        );
    }
}
