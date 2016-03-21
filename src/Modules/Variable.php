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

namespace Ignaszak\Exception\Modules;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
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
    public static function formatVariableType(
        $value,
        string $stringQuotation = ''
    ): string {
        $valueType = gettype($value);

        switch ($valueType) {
            case "boolean":
                return $value ? 'true' : 'false';
            case "integer":
            case "double":
            case "NULL":
                return "{$value}";
            case "string":
                return $stringQuotation . $value . $stringQuotation;
            case "array":
                return print_r($value, true);
            case "object":
                return "($valueType) " . get_class($value);
            case "resource":
                return "($valueType) " . get_resource_type($value);
            case "unknown type":
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
    private static function formatServerDataValue(
        string $value,
        string $key
    ): string {
        $value = str_replace("\n", "", $value);
        $value = strip_tags($value);

        if ($key == 'REQUEST_TIME_FLOAT' || $key == 'REQUEST_TIME') {
            $value = "$value (" . date("c", (int)$value) . ")";
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
