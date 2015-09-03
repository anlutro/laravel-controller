<?php
/**
 * Laravel 4 Controller classes
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-controller
 */

namespace anlutro\LaravelController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * Abstract controller with a lot of handy functions.
 */
abstract class Controller extends AbstractController
{
	/**
	 * Helper function to retrieve this controller's action URLs.
	 * 
	 * @see    action()
	 *
	 * @param  string $action name of the action to look for
	 * @param  array  $params route parameters
	 *
	 * @return string         the URL to the action.
	 */
	protected function url($action, $params = array())
	{
		return URL::action($this->action($action), $params);
	}

	/**
	 * Helper function to redirect to another action in the controller.
	 * 
	 * @see    action()
	 *
	 * @param  string $action name of the action to look for
	 * @param  array  $params (optional) additional parameters
	 * @param  int    $status (optional) status code
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function redirect($action, $params = array(), $status = 302)
	{
		return Redirect::to($this->url($action, $params), $status);
	}

	/**
	 * Create a View instance.
	 *
	 * @param  string $view
	 * @param  array  $data
	 *
	 * @return \Illuminate\View\View
	 */
	protected function view($view, array $data = array())
	{
		return View::make($view, $data);
	}

	/**
	 * Create a view response. Wrapper for Response::view()
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  integer $status
	 * @param  array   $headers
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected function viewResponse($view, $data = array(), $status = 200, array $headers = array())
	{
		return $this->response($this->view($view, $data), $status, $headers);
	}

	/**
	 * If any \ are present, just return the string as is. If no \ are, but @ is
	 * present, takes the current namespace and adds the given controller name.
	 * If \ nor @ are present, takes the current controller class name and
	 * appends the given action.
	 *
	 * @param  string $action
	 *
	 * @return string fully namespaced Controller@Action
	 */
	protected function action($action)
	{
		static $classname;

		if ($classname === null) {
			$classname = $this->getClassName();
		}

		if (strpos($action, '@') === false) {
			return $classname . '@' . $action;
		} elseif (strpos($action, '\\') === false) {
			$namespace = substr($classname, 0, strrpos($classname, '\\'));
			if (substr($action, 0, 1) === '@') {
				return $classname.$action;
			}
			if (!empty($namespace)) {
				return $namespace . '\\' . $action;
			} else {
				return $action;
			}
		} else {
			return $action;
		}
	}

	protected function getClassName()
	{
		return get_class($this);
	}
}
