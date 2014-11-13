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
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Abstract class for basic API functionality.
 */
abstract class ApiController extends AbstractController
{
	/**
	 * Return a generic success response.
	 *
	 * @param  mixed $messages optional
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function success($messages = null)
	{
		$data = $this->getStatusData('success', $messages);
		return $this->jsonResponse($data, 200);
	}

	/**
	 * Return an error response.
	 *
	 * @param  mixed $errors
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function error($errors)
	{
		$data = $this->getStatusData('error', $errors, 'errors');
		return $this->jsonResponse($data, 400);
	}

	/**
	 * Return a generic not found response.
	 *
	 * @param  mixed $messages optional
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function notFound($messages = null)
	{
		$data = $this->getStatusData('not-found', $messages);
		return $this->jsonResponse($data, 404);
	}

	/**
	 * Return a generic status JSON reply.
	 *
	 * @param  string $status
	 * @param  int    $code   HTTP response code
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function status($status, $code)
	{
		$data = $this->getStatusData($status);
		return $this->jsonResponse($data, $code);
	}

	/**
	 * Get an array of a status message response.
	 *
	 * @param  string $status
	 * @param  mixed  $messages optional
	 * @param  string $msgKey   optional
	 *
	 * @return array
	 */
	protected function getStatusData($status, $messages = null, $msgKey = 'messages')
	{
		$data = ['status' => $status];

		if ($messages !== null) {
			if ($messages instanceof MessageProvider) {
				$messages = $messages->getMessageBag();
			}

			if ($messages instanceof Arrayable) {
				$messages = $messages->toArray();
			}

			$data[$msgKey] = (array) $messages;
		}

		return $data;
	}
}
