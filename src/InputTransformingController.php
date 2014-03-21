<?php
namespace anlutro\Core;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Fluent;

trait InputTransformingController
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
		$transformed = $this->fluentInput($key);

		if (is_array($key) || $key === null) {
			return $transformed->toArray();
		} else {
			return isset($transformed[$key]) ? $transformed[$key] : null;
		}
	}

	/**
	 * Get input as a Fluent instance.
	 *
	 * @param  mixed $keys
	 *
	 * @return \Illuminate\Support\Fluent
	 */
	protected function fluentInput($keys = null)
	{
		if ($keys !== null) {
			$input = Input::only($keys);
		} else {
			$input = Input::all();
		}

		return $this->getTransformedInput($input);
	}

	/**
	 * Get the transformed input.
	 *
	 * @param  array $input
	 *
	 * @return \Illuminte\Support\Fluent
	 */
	protected function getTransformedInput($input)
	{
		$transformed = new Fluent($input);

		foreach ($this->transformInput(new Fluent($input)) as $k => $v) {
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
	 * @param  \Illuminte\Support\Fluent $input
	 *
	 * @return array
	 */
	protected function transformInput(Fluent $input)
	{
		return [];
	}
}
