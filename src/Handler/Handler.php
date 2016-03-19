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

use Ignaszak\Exception\Modules\FileContent;
use Ignaszak\Exception\Modules\Variable;
use Ignaszak\Exception\Controller\Controller;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
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
     * @return array
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
        $count = count($backtrace);
        $j = 1;

        for ($i = $forBegin; $i < $count; ++$i) {
            $file = $this->getBacktraceFromKey($backtrace[$i], 'file');
            $line = $this->getBacktraceFromKey($backtrace[$i], 'line');
            $function = $this->getBacktraceFromKey($backtrace[$i], 'function');
            $class = $this->getBacktraceFromKey($backtrace[$i], 'class');
            $type = $this->getBacktraceFromKey($backtrace[$i], 'type');
            $args = $this->getFunctionArgs($this->getBacktraceFromKey($backtrace[$i], 'args'));

            if ($file != '' && $line != '') {
                $array[$j]['message'] = sprintf(
                    "<span class=\"pre\">%-6s</span>%s%s%s%s",
                    "#{$j}",
                    $class,
                    $type,
                    $function,
                    "(<span style=\"color:grey;\">{$this->cutString($args)}</span>)"
                );
                $array[$j]['file']      = "{$file}({$line})";
                $array[$j]['arguments'] = $args;
                $array[$j]['content']   = self::$_fileContent->getFileContent(
                    $file,
                    $line,
                    3
                );
                ++$j;
            }
        }

        return $array;
    }

    /**
     * Returns formated arguments as string
     *
     * @param mixed $args
     * @return string
     */
    private function getFunctionArgs($args): string
    {
        if (!is_array($args)) {
            $args = array($args);
        }
        $array = array();

        foreach ($args as $value) {
            $array[] = Variable::formatVariableType(@$value, "'");
        }

        $stringArgs = implode(', ', $array);
        return $stringArgs;
    }

    /**
     * Checks if passed key exists in backtrace array and returns defined array element
     * (for backtrace['args') returns array)
     *
     * @param array $backtrace
     * @param string $key
     * @return (string|array)
     */
    private function getBacktraceFromKey(array $backtrace, string $key)
    {
        return array_key_exists($key, $backtrace) ? $backtrace[$key] : '';
    }

    /**
     * @param string $string
     * @return string
     */
    private function cutString(string $string): string
    {
        $length = 40;
        return substr($string, 0, $length) . (strlen($string) > $length ? "..." : "");
    }
}
