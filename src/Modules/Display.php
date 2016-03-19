<?php
/**
 * phpDocumentor
 *
 * PHP Version 7.0
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Ignaszak\Exception\Modules;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Controller\IController;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Display
{

    /**
     *
     * @var string
     */
    private $baseDir;

    public function __construct()
    {
        $this->baseDir = dirname(__DIR__) . '/theme/';
    }

    /**
     *
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
     *
     * @param string $file
     */
    private function load(string $file)
    {
        $file = "{$this->baseDir}{$file}";
        if (file_exists($file) && is_readable($file)) {
            include($file);
        }
    }

    /**
     * Method used in theme
     *
     * @return array
     */
    private function getErrorArray(): array
    {
        return IController::getErrorArray();
    }

    /**
     * Method used in theme
     *
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
     * Method used in theme
     *
     * @return string
     */
    private function getUserMessage(): string
    {
        return Conf::get('userMessage');
    }

    /**
     * Method used in theme
     *
     * @param string $inf
     * @return string
     */
    private function getInf(string $inf): string
    {
        switch ($inf) {
            case 'time':
                return date('G:i:s', time());
            case 'path':
                return Conf::get('logFileDir');
            case 'log':
                return Conf::get('createLogFile') ? 'yes' : 'no';
        }
    }
}
