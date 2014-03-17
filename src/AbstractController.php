<?php
/**
 * Laravel 4 Controller classes
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-controller
 */

namespace c;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Contracts\ArrayableInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Abstract class with shared behaviour between all controller types.
 */
abstract class AbstractController
{
	/**
	 * Create a generic response. Wrapper for Response::make()
	 *
	 * @param  string  $content
	 * @param  integer $status
	 * @param  array   $headers
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected function response($content = '', $status = 200, array $headers = array())
	{
		return new Response($content, $status, $headers);
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
		return $this->response(View::make($view, $data));
	}

	/**
	 * Create a JSON response. Wrapper for Response::json()
	 *
	 * @param  array   $data
	 * @param  integer $status
	 * @param  array   $headers
	 * @param  integer $options
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function jsonResponse($data = array(), $status = 200, array $headers = array(), $options = 0)
	{
		if ($data instanceof ArrayableInterface) $data = $data->toArray();

		return new JsonResponse($data, $status, $headers, $options);
	}

	/**
	 * Create a streamed response. Wrapper for Response::stream()
	 *
	 * @param  callable $callback
	 * @param  integer  $status
	 * @param  array    $headers
	 *
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse
	 */
	protected function streamResponse(callable $callback, $status = 200, array $headers = array())
	{
		return new StreamedResponse($callback, $status, $headers);
	}

	/**
	 * Create a file download response. Wrapper for Response::download()
	 *
	 * @param  \SplFileInfo|string  $file
	 * @param  string  $name
	 * @param  array   $headers
	 *
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	protected function downloadResponse($file, $name = null, array $headers = array())
	{
		$response = new BinaryFileResponse($file, 200, $headers, true, 'attachment');

		if ($name !== null) {
			return $response->setContentDisposition('attachment', $name, Str::ascii($name));
		}

		return $response;
	}

	/**
	 * Get the input from the request.
	 *
	 * @param  mixed $key
	 *
	 * @return mixed
	 */
	protected function input($key = null)
	{
		if ($key === null) {
			return Input::all();
		} elseif (is_array($key)) {
			return Input::only($key);
		} else {
			return Input::get($key);
		}
	}
}
