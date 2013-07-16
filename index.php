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

// ------------------------------------------------------------------------

$hours = new HoursArray(array(
    'Sunday'    => array(
        'open'  => '12:00',
        'close' => '22:00',
    ),
));

$s = new Schedule($hours);