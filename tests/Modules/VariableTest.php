<?php
namespace Test\Modules;

use Ignaszak\Exception\Modules\Variable;
use Test\Mock\MockTest;

class VariableTest extends \PHPUnit_Framework_TestCase
{

    public function testGetFormatedServerData()
    {
        $result = Variable::getFormatedServerDataAsString();
        $this->assertRegExp('/<b>\$_SERVER<\/b>/', $result);
    }

    public function testTrueType()
    {
        $this->assertEquals('true', Variable::formatVariableType(true));
    }

    public function testFalseType()
    {
        $this->assertEquals('false', Variable::formatVariableType(false));
    }

    public function testIntType()
    {
        $this->assertEquals('1', Variable::formatVariableType(1));
    }

    public function testDoubleType()
    {
        $this->assertEquals('1.1', Variable::formatVariableType(1.1));
    }

    public function testNULLType()
    {
        $this->assertEquals('NULL', Variable::formatVariableType('NULL'));
    }

    public function testStringType()
    {
        $this->assertEquals(
            "'string'",
            Variable::formatVariableType('string', '\'')
        );
    }

    public function testArrayType()
    {
        $this->assertEquals(
            print_r(['array'], 1),
            Variable::formatVariableType(['array'])
        );
    }

    public function testObjectType()
    {
        $this->assertEquals(
            '(object) Closure',
            Variable::formatVariableType(function () {
            })
        );
    }

    public function testResourceType()
    {
        $f = fopen(MockTest::mockFile('anyFile'), 'r');
        $this->assertEquals(
            '(resource) stream',
            Variable::formatVariableType($f)
        );
    }

    public function testUnknownType()
    {
        $f = fopen(MockTest::mockFile('anyFile'), 'r');
        fclose($f);
        $this->assertEquals('unknown type', Variable::formatVariableType($f));
    }

    public function testFormatServerDataValue()
    {
        $this->assertEquals(
            'AnyValue',
            $this->formatData("Any\nValue", "Any Key")
        );
        $this->assertRegExp(
            '/2016-03-21/',
            $this->formatData("1458583415", "REQUEST_TIME")
        );
    }

    public function testGetServerData()
    {
        $result = MockTest::callMockMethod(
            'Ignaszak\Exception\Modules\Variable',
            'getServerData'
        );
        $this->assertEquals(
            [
                '$_SERVER',
                '$_GET',
                '$_POST',
                '$_SESSION',
                '$_COOKIE',
                '$_FILES',
                '$_ENV'
            ],
            array_keys($result)
        );
    }

    private function formatData(string $value, string $key): string
    {
        return MockTest::callMockMethod(
            'Ignaszak\Exception\Modules\Variable',
            'formatServerDataValue',
            [$value, $key]
        );
    }
}
