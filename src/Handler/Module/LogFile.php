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

namespace Ignaszak\Exception\Handler\Module;

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Handler\IController;

/**
 * Saves log file and generates log file array
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link
 *
 */
class LogFile
{

    /**
     * Stores log files array
     * 
     * @var array
     */
    private static $logFileArray = array();

    /**
     * Gathers informations about Server, execution environment and occured errors
     * and creates log files
     */
    public function createLogFile()
    {
        $logDir = Conf::get('logFileDir');

        if (is_writable($logDir) && Conf::get('createLogFile')) {

            $logDisplayArray = $this->loadLogDisplay();

            $header = "ignaszak/exception - https://github.com/ignaszak/\n"
                   . sprintf("%-17.17s %s", "Date:", date('F d Y H:i:s')) . "\n"
                   . sprintf("%-17.17s %s", "Reported errors:", $logDisplayArray['errorsCount']);

            $globalVars = Variable::getFormatedGlobalVariablesAsString();

            $content = strip_tags("$header\n\n\n$globalVars\n\n\n{$logDisplayArray['errors']}");

            $fileName = md5(time()) . '.log';

            file_put_contents("$logDir/$fileName", $content);
            chmod("$logDir/$fileName", 0664);
        }
    }

    /**
     * Reads log files and adds its contents to log file array
     */
    public function createLogFileListArray()
    {
        $logDir = Conf::get('logFileDir');

        if (is_readable($logDir)) {
            $logDirArray = scandir($logDir);

            $array = array();

            foreach ($logDirArray as $file) {
                if (is_readable("$logDir/$file") && $file != '.' && $file != '..') {
                    $array[] = array(
                        'fileName' => $file,
                        'filemtime' => filemtime("$logDir/$file"),
                        'fileContent' => file_get_contents("$logDir/$file")
                    );
                }
            }

            self::$logFileArray = $array;
            $this->sortLogFileArrayByDate();
        }
    }

    /**
     * @return array
     */
    public static function getLogFileArray()
    {
        return self::$logFileArray;
    }

    /**
     * Returns formated error array
     * 
     * @return array
     */
    private function loadLogDisplay()
    {
        $array = array();
        $count = 1;

        foreach (IController::getErrorArray() as $value) {
            $array[] = "#$count [{$value['type']}]";
            $array[] = "    {$value['message']} in {$value['file']} on line {$value['line']}";
            ++$count;

            if (!empty($value['trace'])) {
                $array[] = "    [Backtrace]:";
                foreach ($value['trace'] as $trace) {
                    $message = str_replace("\n", "\n            ", $trace['message']);
                    $array[] = "        $message in {$trace['file']}";
                }
            }

            $array[] = "\n";
        }

        return array(
            'errors' => implode("\n", $array),
            'errorsCount' => $count - 1
        );
    }

    private function sortLogFileArrayByDate()
    {
        usort(self::$logFileArray,
            function($a, $b)
            {
                return strnatcmp($b['filemtime'], $a['filemtime']);
            }
        );
    }

}
