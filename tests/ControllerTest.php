<?php
namespace anlutro\LaravelController\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;

class ControllerTest extends PHPUnit_Framework_TestCase
{
	protected function makeController()
	{
		return new ControllerStub;
	}

	/**
	 * @test
	 * @dataProvider getActionStringData
	 */
	public function GenerateActionString($input, $expected)
	{
		$controller = $this->makeController();
		$this->assertEquals($expected, $controller->getAction($input));
	}

	public function getActionStringData()
	{
		return [
			['action', __NAMESPACE__.'\ControllerStub@action'],
			['OtherController@action', __NAMESPACE__.'\OtherController@action'],
			['OtherNamespace\Controller@action', 'OtherNamespace\Controller@action'],
		];
	}
}

class ControllerStub extends \anlutro\LaravelController\Controller
{
	public function getAction($input, array $params = array())
	{
		return $this->action($input, $params);
	}
}
