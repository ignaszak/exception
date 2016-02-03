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
 * @link https://github.com/ignaszak/exception/blob/master/src/Handler/Module/FileContent.php
 *
 */
class FileContent
{

    /**
     * Stores file dir
     *
     * @var string
     */
    private $file;

    /**
     * Stores line number
     *
     * @var integer
     */
    private $line;

    /**
     * Stores result of file() function
     *
     * @var array
     */
    private $fileArray = array();

    /**
     * Stores defined offest (default 10)
     *
     * @var integer
     */
    private $offset;

    /**
     * Sets passed arguments to properties and returns generated file fragment
     *
     * @param string $file
     * @param integer $line
     * @param integer $offset
     * @return string
     */
    public function getFileContent(string $file, int $line, int $offset = 10): string
    {
        if (file_exists($file) && is_readable($file) && $line > 0) {
            $this->file = $file;
            $this->line = $line;
            $this->offset = $offset;
            return $this->displayContent();
        }
        return "";
    }

    /**
     * Gets file fragment and creates SyntaxHighlighter <pre> tag
     *
     * @return string
     */
    private function displayContent(): string
    {
        $fileType = pathinfo($this->file, PATHINFO_EXTENSION);
        $firstLine = $this->getBegin() + 1;
        return <<<EOT
<pre class="brush: $fileType; first-line: {$firstLine}; highlight: {$this->line};">
{$this->getFileFragment()}
</pre>
EOT;
    }

    /**
     * @return string
     */
    private function getFileFragment(): string
    {
        $string = "";
        $fileIterator = new \LimitIterator(
            new \SplFileObject($this->file),
            $this->getBegin(),
            $this->offset*2
        );
        foreach ($fileIterator as $line) {
            $string .= $line;
        }
        return $string;
    }

    /**
     * @return integer
     */
    private function getBegin(): int
    {
        $begin = $this->line - $this->offset;
        return $begin < 0 ? 0 : $begin;
    }

    /**
     * @return intger
     */
    private function getEnd(): int
    {
        return $this->line + $this->offset;
    }
}
