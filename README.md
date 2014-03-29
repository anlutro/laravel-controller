# Laravel 4 Controller classes [![Build Status](https://travis-ci.org/anlutro/laravel-controller.png?branch=master)](https://travis-ci.org/anlutro/laravel-controller)
Installation: `composer require anlutro/l4-controller`

Pick the latest stable version from packagist or the GitHub tag list.

WARNING: Backwards compatibility is not guaranteed during version 0.x.

### Controller
`protected function url($action, $params = array())`

This is a shorthand for `URL::action` with a twist - if no controller is given, it defaults to the current controller. For example, if you call `url('index')` from `MyController`, it will return `URL::action('MyController@index')`.

`protected function redirect($action, $params = array())`

Same as above, but for redirects.

### API Controller
Provides some standardized responses when there's no real data to return.

`protected function success($messages = null)`

Returns a generic 200 response with optional messages.

`protected function error($errors)`

Returns a generic 400 response with the errors given. Will work with validators, message bags and arrays.

`protected function notFound($messages = null)`

Returns a generic 404 response with optional messages.

## Contact
Open an issue on GitHub if you have any problems or suggestions.

## License
The contents of this repository is released under the [MIT license](http://opensource.org/licenses/MIT).