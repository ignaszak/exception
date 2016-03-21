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

use Ignaszak\Exception\Conf;
use Ignaszak\Exception\Controller\IController;

/**
 * Saves log file and generates log file array
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class LogFile
{

    /**
     * Gathers informations about Server, execution environment, occured errors
     * and creates log files
     */
    public function createLogFile(): bool
    {
        $logDir = Conf::get('logFileDir');

        if (is_writable($logDir) && Conf::get('createLogFile')) {
            $logDisplayArray = $this->loadLogDisplay();
            $header = "ignaszak/exception - https://github.com/ignaszak/\n" .
                sprintf("%-17.17s %s", "Date:", date('F d Y H:i:s')) . "\n" .
                sprintf(
                    "%-17.17s %s",
                    "Reported errors:",
                    $logDisplayArray['errorsCount']
                );
            $globalVars = Variable::getFormatedServerDataAsString();
            $content = strip_tags(
                "{$header}\n\n\n{$globalVars}\n\n\n{$logDisplayArray['errors']}"
            );
            $fileName = date('Y_m_d_H_i_s_u', time()) . '.log';
            if (file_put_contents("{$logDir}/{$fileName}", $content)) {
                chmod("{$logDir}/{$fileName}", 0664);
                return true;
            }
        }
        return false;
    }

    /**
     * Returns formated error array
     *
     * @return array
     */
    private function loadLogDisplay(): array
    {
        $array = array();
        $count = 1;

        foreach (IController::getErrorArray() as $value) {
            $array[] = "#$count [{$value['type']}]";
            $array[] = "    {$value['message']} in {$value['file']} " .
                "on line {$value['line']}";
            ++$count;

            if (!empty($value['trace'])) {
                $array[] = "    [Backtrace]:";
                foreach ($value['trace'] as $trace) {
                    $message = str_replace(
                        "\n",
                        "\n            ",
                        $trace['message']
                    );
                    $array[] = "        {$message}";
                    $array[] = "              IN: {$trace['file']}";
                    $array[] = "              ARGUMENTS:";
                    $array[] = "                  " .
                        str_replace(
                            "\n",
                            "\n                  ",
                            $trace['arguments']
                        );
                }
            }

            $array[] = "\n";
        }

        return array(
            'errors' => implode("\n", $array),
            'errorsCount' => $count - 1
        );
    }
}
