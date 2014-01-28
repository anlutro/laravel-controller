<?php
namespace c;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Fluent;

abstract class AbstractController extends \Illuminate\Routing\Controller
{
	/**
	 * Get the input from the request.
	 *
	 * @param  mixed $key
	 *
	 * @return mixed
	 */
	protected function input($key = null)
	{
		if ($key !== null) {
			$input = Input::only($key);
		} else {
			$input = Input::all();
		}

		$transformed = $this->getTransformedInput($input);

		if (is_array($key) || $key === null) {
			return $transformed;
		} else {
			return isset($transformed[$key]) ? $transformed[$key] : null;
		}
	}

	/**
	 * Get the transformed input.
	 *
	 * @param  array $input
	 *
	 * @return Illuminte\Support\Fluent
	 */
	protected function getTransformedInput($input)
	{
		$fluent = new Fluent($input);
		$transformed = new Fluent($input);

		foreach ($this->transformInput($fluent) as $k => $v) {
			if (array_key_exists($k, $input)) {
				if ($v instanceof \Closure) {
					$transformed[$k] = $v($input[$k]);
				} else {
					$transformed[$k] = $v;
				}
			}
		}

		return $transformed;
	}

	/**
	 * Transform input.
	 *
	 * @param  Fluent $input
	 *
	 * @return array
	 */
	protected function transformInput(Fluent $input)
	{
		return [];
	}
}
