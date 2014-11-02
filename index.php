<?php

require_once __DIR__ . '/vendor/autoload.php';

use Whoops\Handler\PrettyPageHandler;

// Set up pretty errors.
$run = new Whoops\Run;
$handler = new PrettyPageHandler;

$run->pushHandler($handler);

$run->register();

if ( ! function_exists('dd'))
{
	/**
	 * Dump the passed variables and end the script.
	 *
	 * @param  dynamic  mixed
	 * @return void
	 */
	function dd()
	{
		array_map(function($x) { var_dump($x); }, func_get_args()); die;
	}
}

// ------------------------------------------------------------------------

echo 'Nothing :(';
