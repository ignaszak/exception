<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Ignaszak\Exception\Handler\Module;

use Ignaszak\Exception\Conf;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Module/Display.php
 *
 */
class Display
{

    public function loadDisplay($display)
    {
        if (Conf::get('display') == $display && $display != 'location') {
            $baseDIR = dirname(dirname(__DIR__));
            $file = $baseDIR . "/theme/$display.html";

            if (file_exists($file) && is_readable($file))
                include $file;
        }
    }

    public function loadLocation()
    {
        $location = Conf::get('userLocation');

        if (Conf::get('display') == 'location' && !empty($location)) {
            header('Location: ' . $location);
            if (@TEST_MODE !== true) exit;
        }
    }

}
