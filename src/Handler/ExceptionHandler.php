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

namespace Ignaszak\Exception\Handler;

/**
 * Exceptions handler
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/ExceptionHandler.php
 *
 */
class ExceptionHandler extends Handler
{

    /**
     * Runs set_exception_handler function
     * Sets default error type for exceptions as E_ERROR
     */
    public function setExceptionHandler()
    {
        set_exception_handler(function($e) {

            parent::$_controller->catchErrorAndHandle(array(
                'Uncaught exception: ' . get_class($e), // Exception name as error type
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                parent::$_fileContent->getFileContent($e->getFile(), $e->getLine()),
                $this->getTrace($e),
                $e->getPrevious(),
                $e->getCode()
            ));

            parent::$_controller->addReportedErrorCode(E_ERROR);
        });
    }

    /**
     * Catchs exception
     * 
     * @param object $e
     * @param integer $type
     */
    public function catchException($e, int $type = E_ERROR)
    {
        if ($this->isRightErrorType($type) && ini_get('error_reporting') & $type) {
            parent::$_controller->catchErrorAndHandle(array(
                get_class($e), // Exception name as error type
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                parent::$_fileContent->getFileContent($e->getFile(), $e->getLine()),
                $this->getTrace($e),
                $e->getPrevious(),
                $e->getCode()
            ));

            parent::$_controller->addReportedErrorCode($type);
        }
    }

    /**
     * Checks if $type is right error type
     * 
     * @param integer $type
     * @return boolean
     */
    private function isRightErrorType(int $type): bool
    {
        $errorTypeArray = array(
            E_ERROR,
            E_WARNING,
            E_PARSE,
            E_NOTICE,
            E_CORE_ERROR,
            E_CORE_WARNING,
            E_COMPILE_ERROR,
            E_COMPILE_WARNING,
            E_USER_ERROR,
            E_USER_WARNING,
            E_USER_NOTICE,
            E_STRICT,
            E_RECOVERABLE_ERROR,
            E_DEPRECATED,
            E_USER_DEPRECATED,
            E_RECOVERABLE_ERROR
        );

        return in_array($type, $errorTypeArray);
    }

}
