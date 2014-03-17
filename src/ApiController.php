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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

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
		return Response::json($data, 200);
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
		return Response::json($data, 400);
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
		return Response::json($data, 404);
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
		return Response::json($data, $code);
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
			if ($messages instanceof MessageProviderInterface) {
				$messages = $messages->getMessageBag();
			}

			if ($messages instanceof ArrayableInterface) {
				$messages = $messages->toArray();
			}

			$data[$msgKey] = (array) $messages;
		}

		return $data;
	}
}
