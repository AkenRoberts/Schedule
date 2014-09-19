<?php

require_once __DIR__ . '/vendor/autoload.php';

use Cryode\Schedule\Hours\HoursArray;
use Cryode\Schedule\Schedule;

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

$hours = new HoursArray(array(
    'Sunday'    => array(
        'open'  => '12:00',
        'close' => '22:00',
    ),
));

$s = new Schedule($hours);