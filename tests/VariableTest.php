<?php

namespace Test;

use Ignaszak\Exception\Modules\Variable;

class VariableTest extends \PHPUnit_Framework_TestCase
{

    public function testFormatVariableType()
    {
        $formatVariableType = array(
            Variable::formatVariableType(1),
            Variable::formatVariableType('string', "'"),
            Variable::formatVariableType(array(1=>1,2=>2)),
            Variable::formatVariableType(new \Exception('test')),
            Variable::formatVariableType(fopen(__FILE__, "r"))
        );

        $array = array(
            1,
            '\'string\'',
            print_r(array(1=>1,2=>2), true),
            '(object) Exception',
            '(resource) stream'
        );

        $this->assertEquals($array, $formatVariableType);
    }
}
