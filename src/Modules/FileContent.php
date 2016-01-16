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
    private $fileFragmentOffset;

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
            $this->setArgs($file, $line, $offset);
            $this->loadFileToArray();
            return $this->displayContent();
        }
        return '';
    }

    /**
     * @param string $file
     * @param int $line
     * @param int $offset
     */
    private function setArgs(string $file, int $line, int $offset = null)
    {
        $this->file = $file;
        $this->line = $line;
        if ($offset > 0)
            $this->fileFragmentOffset = $offset;
    }

    private function loadFileToArray()
    {
        $this->fileArray = file($this->file);
    }

    /**
     * Gets file fragment and creates SyntaxHighlighter <pre> tag
     * 
     * @return string
     */
    private function displayContent(): string
    {
        $fragment = $this->getFileFragment();
        $firstLine = $this->line - $this->fileFragmentOffset;
        $firstLine = ($firstLine < 0 ? 1 : $firstLine);
        $fileType = pathinfo($this->file, PATHINFO_EXTENSION);

        return "<pre class=\"brush: $fileType; first-line: $firstLine; highlight: {$this->line};\">"
               . $fragment . "</pre>";
    }

    /**
     * Based on array_slice function, defined offset and line returns file fragment
     * 
     * @return string
     */
    private function getFileFragment(): string
    {
        $start = ($this->line - ($this->fileFragmentOffset + 1));
        $start = ($start < 0 ? 0 : $start);

        $offset = (2*($this->fileFragmentOffset + 1) - 1);

        $fragment = array_slice($this->fileArray, $start, $offset);
        $fragment = htmlspecialchars(implode('', $fragment));

        return str_replace("\n", "&nbsp;\n", $fragment);
    }

}
