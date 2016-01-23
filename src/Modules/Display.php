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

namespace Ignaszak\Exception\Modules;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Controller\IController;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Module/Display.php
 *
 */
class Display
{

    /**
     * @var string
     */
    private $baseDir;

    public function __construct()
    {
        $this->baseDir = dirname(__DIR__) . '/theme/';
    }

    /**
     * @param string $display
     */
    public function loadDisplay(string $display)
    {
        if (Conf::get('display') == $display && $display != 'location') {
            $this->load("/{$display}.html");
        }
    }

    public function loadLocation()
    {
        $location = Conf::get('userLocation');

        if (Conf::get('display') == 'location' && !empty($location)) {
            header('Location: ' . $location);
            if (@TEST_MODE !== true) {
                exit;
            }
        }
    }

    /**
     * @return array
     */
    private function getErrorArray(): array
    {
        return IController::getErrorArray();
    }

    /**
     * @return string
     */
    private function getServerData(): string
    {
        return str_replace(
            "\n",
            "<br>",
            str_replace(
                " ",
                "&nbsp;",
                Variable::getFormatedServerDataAsString()
            )
        );
    }

    /**
     * @return string
     */
    private function getUserMessage(): string
    {
        return Conf::get('userMessage');
    }

    /**
     * @param string $file
     */
    private function load(string $file)
    {
        $file = "{$this->baseDir}{$file}";
        if (file_exists($file) && is_readable($file)) {
            include($file);
        }
    }

    private function getInf(string $inf): string
    {
        switch ($inf) {
            case 'time':
                return date('G:i:s', time());
            break;
            case 'path':
                return Conf::get('logFileDir');
            break;
            case 'log':
                return Conf::get('createLogFile') ? 'yes' : 'no';
            break;
        }
    }
}
