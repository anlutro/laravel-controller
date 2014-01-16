<?php
/**
 * Laravel 4 Controller classes
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-controller
 */

namespace c;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * Abstract controller with a lot of handy functions.
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
	/**
	 * Helper function to retrieve this controller's action URLs.
	 * 
	 * @see    parseAction
	 *
	 * @param  string $action name of the action to look for
	 * @param  array  $params route parameters
	 *
	 * @return string         the URL to the action.
	 */
	protected function url($action, $params = array())
	{
		return URL::action($this->parseAction($action), $params);
	}

	/**
	 * @deprecated - use url()
	 */
	protected function urlAction()
	{
		return call_user_func_array([$this, 'url'], func_get_args());
	}

	/**
	 * Helper function to redirect to another action in the controller.
	 * 
	 * @see    parseAction
	 *
	 * @param  string $action name of the action to look for
	 * @param  array  $params (optional) additional parameters
	 *
	 * @return Redirect       a Redirect response.
	 */
	protected function redirect($action, $params = array())
	{
		return Redirect::to($this->url($action, $params));
	}

	/**
	 * @deprecated - use redirect()
	 */
	protected function redirectAction()
	{
		return call_user_func_array([$this, 'redirect'], func_get_args());
	}

	/**
	 * Create a View instance.
	 *
	 * @param  string $view
	 * @param  array  $data
	 *
	 * @return Illuminate\View\View
	 */
	public function view($view, array $data = array())
	{
		return View::make($view, $data);
	}

	/**
	 * Get the input from the request.
	 *
	 * @param  mixed $key
	 *
	 * @return mixed
	 */
	public function input($key = null)
	{
		if (is_array($key)) {
			return Input::only($key);
		}

		if ($key !== null) {
			return Input::get($key);
		}

		return Input::all();
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
	protected function parseAction($action)
	{
		static $classname;

		if ($classname === null) {
			$classname = get_class($this);
		}

		if (strpos($action, '@') === false) {
			return $classname . '@' . $action;
		} elseif (strpos($action, '\\') === false) {
			$namespace = substr($classname, 0, strrpos($classname, '\\'));
			if (!empty($namespace)) {
				return $namespace . '\\' . $action;
			} else {
				return $action;
			}
		} else {
			return $action;
		}
	}
}
