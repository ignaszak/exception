<?php
/**
 * phpDocumentor
 *
 * PHP Version 7.0
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
declare(strict_types=1);

namespace Ignaszak\Exception;

/**
 * Stores configuration settings
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Conf
{

    /**
     * Stores instance of Conf class
     *
     * @var Conf
     */
    private static $_conf;

    /**
     * Stores error reporting directive
     *
     * @var integer
     */
    private $errorReporting = null;

    /**
     * Stores display mode if error occured:
     * 'dev'      - full developer error interface
     * 'user'     - simple message for users
     * 'location' - redirect to oder site with custom error message
     * 'none'     - set no display
     *
     * @var string (dev|user|location|null)
     */
    private $display = '';

    /**
     * Stores error level displayed in 'user' or 'location' mode
     *
     * @var integer
     */
    private $userReporting = null;

    /**
     * Stores message for 'user' mode
     *
     * @var string
     */
    private $userMessage = '';

    /**
     * Stores location adress for 'location' mode
     *
     * @var string
     */
    private $userLocation = '';

    /**
     * Create log file if error occured
     *
     * @var boolean
     */
    private $createLogFile = false;

    /**
     * Log files dir
     *
     * @var string
     */
    private $logFileDir = '';

    /**
     * @return \Ignaszak\Exception\Conf
     */
    public static function instance()
    {
        if (empty(self::$_conf)) {
            self::$_conf = new Conf;
        }

        return self::$_conf;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function setProperty(string $property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * @param string $property
     * @return mixed
     */
    public static function get(string $property)
    {
        return property_exists(self::$_conf, $property) ?
            self::$_conf->$property : null;
    }
}
