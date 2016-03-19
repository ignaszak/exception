<?php
/**
 * phpDocumentor
 *
 * PHP Version 7.0
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Ignaszak\Exception\Handler;

/**
 * Provides methods to handle errors
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class ErrorHandler extends Handler
{

    /**
     * Calls set_error_handler function and passes created error array to controller
     */
    public function setErrorHandler()
    {
        set_error_handler(function ($errorNumber, $errorMessage, $errorFile, $errorLine) {

            parent::$_controller->catchErrorAndHandle(array(
                $this->getErrorTypeByNumber($errorNumber),
                $errorMessage,
                $errorFile,
                $errorLine,
                parent::$_fileContent->getFileContent($errorFile, $errorLine),
                $this->getTrace()
            ));

            parent::$_controller->addReportedErrorCode($errorNumber);

        });
    }

    /**
     * Calls register_shutdown_function function and Controller::_destruct()
     * and passes created error array to controller
     */
    public function setShutdownHandler()
    {
        register_shutdown_function(function () {

            $lastError = error_get_last();

            if (count($lastError)) { // If not empty
                parent::$_controller->catchErrorAndHandle(array(
                    $this->getErrorTypeByNumber($lastError['type']),
                    $lastError['message'],
                    $lastError['file'],
                    $lastError['line'],
                    parent::$_fileContent->getFileContent($lastError['file'], $lastError['line']),
                    $this->getTrace()
                ));

                parent::$_controller->addReportedErrorCode($lastError['type']);
                parent::$_controller->__destruct();
            }

        });
    }

    /**
     * If error is not suppressed returns error type
     *
     * @param integer $errorNumber
     * @return string
     */
    private function getErrorTypeByNumber(int $errorNumber): string
    {
        $errorNumber &= error_reporting(E_ALL);
        switch ($errorNumber) {
            case 0:
                return "";
            case E_ERROR:
                return "Fatal error";
            case E_WARNING:
                return "Warning";
            case E_PARSE:
                return "Parse error";
            case E_NOTICE:
                return "Notice";
            case E_CORE_ERROR:
                return "Core error";
            case E_CORE_WARNING:
                return "Core warning";
            case E_COMPILE_ERROR:
                return "Compile error";
            case E_COMPILE_WARNING:
                return "Compile warning";
            case E_USER_ERROR:
                return "User error";
            case E_USER_WARNING:
                return "User warning";
            case E_USER_NOTICE:
                return "User notice";
            case E_STRICT:
                return "Strict notice";
            case E_RECOVERABLE_ERROR:
                return "Recoverable error";
            case E_DEPRECATED:
                return "Deprecated error";
            case E_USER_DEPRECATED:
                return "User deprecated error";
            case E_RECOVERABLE_ERROR:
                return "Recoverable error";
            default:
                return "Unknown error ($errorNumber)";
        }
    }
}
