<?php

namespace Cryode\Schedule;

require_once 'vendor/autoload.php';

// ------------------------------------------------------------------------

$hours = new Hours\HoursArray(array(
    'Sunday'    => array(
        'open'  => '12:00',
        'close' => '22:00',
    ),
));

$schedule = new Schedule($hours);

exit(var_dump($schedule));