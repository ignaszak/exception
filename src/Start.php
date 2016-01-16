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

use Ignaszak\Exception\Handler\ErrorHandler;
use Ignaszak\Exception\Handler\ExceptionHandler;

/**
 * Initializes handler
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Start.php
 *
 */
class Start
{

    /**
     * Stores ErrorHandler instance
     * 
     * @var ErrorHandler
     */
    private $_errorHandler;

    /**
     * Stores ExceptionHandler instance
     *  
     * @var ExceptionHandler
     */
    private $_exceptionHandler;

    /**
     *  Stores Conf instance
     *  
     * @var Conf
     */
    private $_conf;

    public function __construct()
    {
        $this->_conf = Conf::instance();
        $this->_errorHandler = new ErrorHandler;
        $this->_exceptionHandler = new ExceptionHandler;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value) {
        $this->_conf->setProperty($property, $value);
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Exception\Handler\ExceptionHandler::catchException()
     */
    public function catchException($e, $type = E_ERROR)
    {
        $this->_exceptionHandler->catchException($e, $type);
    }

    /**
     * Initializes handlers, sets error reporting and if an error or exception
     * occured sets ob_start function
     */
    public function run()
    {
        if (Conf::get('display') == 'dev' || Conf::get('display') == 'user') {
            $this->_errorHandler->setErrorHandler();
            $this->_errorHandler->setShutdownHandler();
            $this->_exceptionHandler->setExceptionHandler();
            $this->errorReporting();
            $this->setObStart();
        }
    }

    private function errorReporting()
    {
        if (!empty(Conf::get('errorReporting')))
            error_reporting(Conf::get('errorReporting'));
    }

    private function setObStart()
    {
        ob_start();
    }

}
