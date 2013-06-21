<?php

use Cryode\Schedule\Schedule;
use Cryode\Schedule\Hours\HoursArray;

// Aken's dev server has errors turned off...
ini_set('display_errors', 1);
error_reporting(E_ALL);

// PSR-0 autoload function
spl_autoload_register(function($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
});

// ------------------------------------------------------------------------

$hours = new HoursArray(array(
    'Sunday'    => array(
        'open'  => '12:00',
        'close' => '22:00',
    ),
));

$schedule = new Schedule($hours);

exit(var_dump($schedule));