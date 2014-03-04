<?php
/**
 * Laravel 4 Controller classes
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-controller
 */

namespace c;

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
	 * @param  int    $status (optional) status code
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function redirect($action, $params = array(), $status = 302)
	{
		return Redirect::to($this->url($action, $params), 302);
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
	 * @return \Illuminate\View\View
	 */
	protected function view($view, array $data = array())
	{
		return View::make($view, $data);
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

	/**
	 * @deprecated - use action()
	 */
	protected function parseAction()
	{
		return call_user_func_array([$this, 'action'], func_get_args());
	}
}
