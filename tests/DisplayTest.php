<?php

namespace Test;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Modules\Display;

class DisplayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testLoadLocation()
    {
        define('TEST_MODE', true);
        $location = 'http://example.com';

        $_conf = Conf::instance();
        $_conf->setProperty('userLocation', $location);
        $_conf->setProperty('display', 'location');

        $_display = new Display;
        $_display->loadLocation();

        $headers_list = xdebug_get_headers();
        $this->assertContains("Location: $location", $headers_list);
    }

}
