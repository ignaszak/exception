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

namespace Ignaszak\Exception\Controller;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
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
