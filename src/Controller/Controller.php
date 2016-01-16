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

namespace Ignaszak\Exception\Controller;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Modules\Display;
use Ignaszak\Exception\Modules\LogFile;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Controller.php
 *
 */
class Controller extends IController
{

    /**
     * Stores instance of Controller class
     *
     * @var Controller
     */
    private static $_controller;

    /**
     * Stores instance of Display class
     *
     * @var Display
     */
    private $_display;

    /**
     * Stores instance of LogFile class
     *
     * @var LogFile
     */
    private $_logFile;

    /**
     * Stores reported error and exception level (for exceptions default is E_ERROR
     * but it can be changed in Start::catchException($e [, $error_level]) function)
     * 
     * @var integer
     */
    private static $reportedErrorCode;

    /**
     * Counter used to prevent multi-call __destruct method
     * 
     * @var integer
     */
    private static $count = 0;

    public function __construct()
    {
        $this->_display = new Display;
        $this->_logFile = new LogFile;
    }

    /**
     * @return Controller
     */
    public static function instance(): Controller
    {
        if (empty(self::$_controller))
            self::$_controller = new Controller;

            return self::$_controller;
    }

    /**
     * If $error is not empty and invoked error is not suppressed
     * adds passed error array to IController::$errorArray
     * 
     * @param array $error
     */
    public function catchErrorAndHandle(array $error)
    {
        if ($this->isArrayNotEmpty($error) && $this->isErrorNotSuppressed($error)) {
            $errorArray = $this->renameArrayKeys($error);
            parent::$errorArray[] = $errorArray;
        }
    }

    public function addReportedErrorCode($code)
    {
        self::$reportedErrorCode .= $code;
    }

    public function __destruct()
    {
        if ($this->isArrayNotEmpty(parent::$errorArray) && !self::$count) {
            $this->cleanBuffer();

            $this->_logFile->createLogFile();
            $this->_display->loadDisplay('dev');

            if ($this->isUserReporting()) {
                $this->_display->loadDisplay('user');
                $this->_display->loadLocation();
            }

        }

        ++self::$count;
    }

    public function cleanBuffer()
    {
        if (ob_get_contents())
            ob_clean();
    }

    /**
     * Returns true if occcured error level is defined in 'userReporting' setting
     * 
     * @return boolean
     */
    private function isUserReporting(): bool
    {
        if (self::$reportedErrorCode & Conf::get('userReporting')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns false if error was suppressed with the @-operator
     * (first array parameter - error type)
     * 
     * @param array $error
     * @return boolean
     */
    private function isErrorNotSuppressed(array $error): bool
    {
        return $error[0];
    }

    /**
     * Converts indexed array into associative 
     * 
     * @param array $array
     * @return array
     */
    private function renameArrayKeys(array $array): array
    {
        $arrayKeyPattern = array(
            'type',
            'message',
            'file',
            'line',
            'content',
            'trace',
            'previous',
            'code'
        );
        $renameArray = array();

        for ($i=0; $i<count($array); ++$i) {
            $renameArray[ $arrayKeyPattern[$i] ] = $array[$i];
        }

        return $renameArray;
    }

    /**
     * Returns true if minimum one of elements is not empty
     * 
     * @param array $array
     * @return boolean
     */
    private function isArrayNotEmpty(array $array): bool
    {
        $i = 0;
        foreach ($array as $value) {
            if (!empty($value)) ++$i;
        }
        return $i;
    }

}
