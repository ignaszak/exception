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

namespace Ignaszak\Exception;

/**
 * Stores configuration settings
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Conf.php
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
    private $errorReporting;

    /**
     * Stores display mode if error occured:
     * 'dev'      - full developer error interface
     * 'user'     - simple message for users
     * 'location' - redirect to oder site with custom error message
     * 'none'     - set no display
     * 
     * @var string (dev|user|location|null)
     */
    private $display;

    /**
     * Stores error level displayed in 'user' or 'location' mode
     * 
     * @var integer
     */
    private $userReporting;

    /**
     * Stores message for 'user' mode
     * 
     * @var string
     */
    private $userMessage;

    /**
     * Stores location adress for 'location' mode
     * 
     * @var string
     */
    private $userLocation;

    /**
     * Create log file if error occured
     * 
     * @var boolean
     */
    private $createLogFile;

    /**
     * Log files dir
     * 
     * @var string
     */
    private $logFileDir;

    /**
     * @return \Ignaszak\Exception\Conf
     */
    public static function instance()
    {
        if (empty(self::$_conf))
            self::$_conf = new Conf;

        return self::$_conf;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function setProperty($property, $value)
    {
        if (property_exists($this, $property))
            $this->$property = $value;
    }

    /**
     * @param string $property
     * @return string
     */
    public static function get($property)
    {
        if (property_exists(self::$_conf, $property))
            return self::$_conf->$property;
    }

}
