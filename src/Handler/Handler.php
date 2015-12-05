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

use Ignaszak\Exception\Handler\Module\FileContent;
use Ignaszak\Exception\Handler\Module\Variable;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Handler.php
 *
 */
abstract class Handler
{

    /**
     * Stores instance of Controller class
     * 
     * @var Controller
     */
    protected static $_controller;

    /**
     * Stores instance of FileContent class
     * 
     * @var FileContent
     */
    protected static $_fileContent;

    public function __construct()
    {
        self::$_fileContent = new FileContent;
        self::$_controller = Controller::instance();
    }

    /**
     * Returns backtrace for errors and exceptions as an array
     * 
     * @param object $e
     * @return (array|null)
     */
    protected function getTrace($e = null)
    {
        if ($e) {
            $backtrace = $e->getTrace();
            $forBegin = 0;
        } else {
            $backtrace = debug_backtrace();
            $forBegin = 2;
        }

        $array = array();
        $count = 0;

        for ($i=$forBegin; $i<count($backtrace); ++$i) {
            $file = $this->getBacktraceFromKey($backtrace[$i], 'file');
            $line = $this->getBacktraceFromKey($backtrace[$i], 'line');
            $function = $this->getBacktraceFromKey($backtrace[$i], 'function');
            $class = $this->getBacktraceFromKey($backtrace[$i], 'class');
            $type = $this->getBacktraceFromKey($backtrace[$i], 'type');
            $args = $this->getFunctionArgs($this->getBacktraceFromKey($backtrace[$i], 'args'));

            $array[$count]['message']  = "#$count $class$type$function(<span style=\"color:grey;\">$args</span>)";
            $array[$count]['file']     = "$file($line)";
            $array[$count]['content']  = self::$_fileContent->getFileContent($file, $line, 3);

            ++$count;
        }

        return ($count ? $array : null);
    }

    /**
     * Returns formated arguments as string
     * 
     * @param array $args
     * @return string
     */
    private function getFunctionArgs($args)
    {
        $array = array();

        foreach ($args as $value) {
            $array[] = Variable::formatVariableType($value, "'");
        }

        $stringArgs = implode(', ', $array);
        return $this->cutString($stringArgs);
    }

    /**
     * Checks if passed key exists in backtrace array and returns defined array element
     * (for backtrace['args') returns array)
     * 
     * @param array $backtrace
     * @param string $key
     * @return (string|array|null)
     */
    private function getBacktraceFromKey($backtrace, $key)
    {
        return array_key_exists($key, $backtrace) ? $backtrace[$key] : null;
    }

    /**
     * @param string $string
     * @return string
     */
    private function cutString($string)
    {
        $length = 40;
        return substr($string, 0, $length) . (strlen($string) > $length ? "..." : "");
    }

}
