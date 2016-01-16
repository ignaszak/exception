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

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/IController.php
 *
 */
abstract class IController
{

    /**
     * @var array
     */
    protected static $errorArray = array();

    abstract public function catchErrorAndHandle(array $error);
    abstract public function addReportedErrorCode($code);
    abstract public function __destruct();
    abstract public function cleanBuffer();

    /**
     * @return array
     */
    public static function getErrorArray(): array
    {
        return self::$errorArray;
    }

}
