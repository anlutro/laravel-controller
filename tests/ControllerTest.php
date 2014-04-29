<?php
namespace anlutro\LaravelController\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use Illuminate\Support\Facades;

class ControllerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @dataProvider getActionStringData
	 */
	public function generateActionString($controller, $action, $expected)
	{
		$this->assertEquals($expected, $controller->getAction($action));
		Facades\URL::shouldReceive('action')->times(2)->with($expected, [])->andReturn('fakeurl');
		Facades\Redirect::shouldReceive('to')->once()->with('fakeurl', 302)->andReturn('fakeredirect');
		$this->assertEquals('fakeurl', $controller->getUrl($action));
		$this->assertEquals('fakeredirect', $controller->getRedirect($action));
	}

	public function getActionStringData()
	{
		return [
			[new ControllerStub, 'action', __NAMESPACE__.'\ControllerStub@action'],
			[new ControllerStub, '@action', __NAMESPACE__.'\ControllerStub@action'],
			[new ControllerStub, 'OtherController@action', __NAMESPACE__.'\OtherController@action'],
			[new ControllerStub, 'OtherNamespace\Controller@action', 'OtherNamespace\Controller@action'],
			[new SecondControllerStub, 'action', __NAMESPACE__.'\SecondControllerStub@action'],
			[new SecondControllerStub, '@action', __NAMESPACE__.'\SecondControllerStub@action'],
			[new SecondControllerStub, 'OtherController@action', __NAMESPACE__.'\OtherController@action'],
			[new SecondControllerStub, 'OtherNamespace\Controller@action', 'OtherNamespace\Controller@action'],
		];
	}
}

class ControllerStub extends \anlutro\LaravelController\Controller
{
	public function getAction($action)
	{
		return $this->action($action);
	}

	public function getUrl($action)
	{
		return $this->url($action);
	}

	public function getRedirect($action)
	{
		return $this->redirect($action);
	}
}

class SecondControllerStub extends ControllerStub {}
