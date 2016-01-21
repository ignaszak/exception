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

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Module/Variable.php
 *
 */
class Variable
{

    /**
     * Returns server and execution environment information
     *
     * @return string
     */
    public static function getFormatedServerDataAsString(): string
    {
        $array = array();

        foreach (self::getServerData() as $key => $variable) {
            if (!empty($variable)) {

                $array[] = "<b>$key</b>";

                foreach ($variable as $key => $value) {
                    $value = self::formatVariableType($value);
                    $value = self::formatServerDataValue($value, $key);
                    $array[] = sprintf(
                        " %-33.33s %s",
                        "[$key]:",
                        $value
                    );
                }
            }
        }

        return implode("\n", $array);
    }

    /**
     * Changes passed $value to pirntable version
     *
     * @param mixed $value
     * @param string $stringQuotation
     */
    public static function formatVariableType($value, string $stringQuotation = ''): string
    {
        $valueType = gettype($value);

        switch ($valueType) {
            case "boolean":
            case "integer":
            case "double":
            case "NULL":
                return "{$value}";
                break;
            case "string":
                return $stringQuotation . $value . $stringQuotation;
                break;
            case "array":
                return print_r($value, true);
                break;
            case "object":
                return "($valueType) " . get_class($value);
                break;
            case "resource":
                return "($valueType) " . get_resource_type($value);
                break;
            case "unknown type":
                return $valueType;
                break;
            default:
                return $valueType;
        }
    }

    /**
     * Deletes new lines, hatml tags and adds formated date for time
     *
     * @param string $value
     * @param string $key
     * @return string
     */
    private static function formatServerDataValue(string $value, string $key): string
    {
        $value = str_replace("\n", "", $value);
        $value = strip_tags($value);

        if ($key == 'REQUEST_TIME_FLOAT' || $key == 'REQUEST_TIME') {
            $value = "$value (" . date("c", $value) . ")";
        }

            return $value;
    }

    /**
     * @return array
     */
    private static function getServerData(): array
    {
        return array(
            '$_SERVER'  => $_SERVER,
            '$_GET'     => $_GET,
            '$_POST'    => $_POST,
            '$_SESSION' => @$_SESSION,
            '$_COOKIE'  => $_COOKIE,
            '$_FILES'   => $_FILES,
            '$_ENV'     => $_ENV
        );
    }
}
