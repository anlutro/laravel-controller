<?php

use Mockery as m;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades;

class InputTransformationTest extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('UTC');
	}

	public function tearDown()
	{
		Facades\Facade::clearResolvedInstances();
		m::close();
	}

	public function testAllInputIsTransformed()
	{
		$obj = new StdClass; $obj->foo = 'bar';
		$this->setInput('all', [
			'number1' => '1',
			'number2' => '2',
			'stringnum' => 4.5,
			'strupper' => 'hello',
			'array' => 'foo',
			'objtoarr' => $obj,
			'object' => ['foo' => 'bar'],
			'datetime' => '2014-01-01 12:00:00',
			'untransformed' => 'untransformed',
		]);
		$result = (new CtrlStub)->getInput();
		$this->assertSame(1, $result['number1']);
		$this->assertSame(2.0, $result['number2']);
		$this->assertSame('4.5', $result['stringnum']);
		$this->assertSame('HELLO', $result['strupper']);
		$this->assertSame(['foo'], $result['array']);
		$this->assertSame(['foo' => 'bar'], $result['objtoarr']);
		$this->assertInstanceOf('StdClass', $result['object']);
		$this->assertEquals('bar', $result['object']->foo);
		$this->assertInstanceOf('DateTime', $result['datetime']);
		$this->assertEquals('2014-01-01 12:00:00', $result['datetime']->format('Y-m-d H:i:s'));
		$this->assertEquals('untransformed', $result['untransformed']);
	}

	public function testGetSingleInput()
	{
		$this->setInput('only', ['foo' => 'bar', 'bar' => 'baz']);
		$result = (new CtrlStub)->getInput('foo');
		$this->assertEquals('bar', $result);
	}

	public function setInput($method, array $input)
	{
		Facades\Input::shouldReceive($method)->once()->andReturn($input);
	}
}

class CtrlStub
{
	use anlutro\LaravelController\InputTransformingController;

	public function getInput($key = null)
	{
		return $this->input($key);
	}

	protected function transformInput(Fluent $input)
	{
		return [
			'number1' => (int) $input->number1,
			'number2' => (float) $input->number2,
			'stringnum' => (string) $input->stringnum,
			'strupper' => strtoupper($input->strupper),
			'array' => (array) $input->array,
			'objtoarr' => (array) $input->objtoarr,
			'object' => json_decode(json_encode($input->object)),
			'datetime' => function($datetime) {
				return DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
			},
		];
	}
}
