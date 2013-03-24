<?php

require_once 'Interface.php';

class Schedule_HoursAbstract implements Schedule_HoursInterface {


    /**
     * Container for the hours
     *
     * @var array
     */
    protected $hours = array();
    /**
     * Valid days of the week. Matches output of date('l')
     * @var array
     */
    protected $daysofweekArray = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    /**
     * [__construct description]
     */
    public function __construct($hours)
    {
        // validate all open and close strings
        foreach ($hours as &$day) {
            if (array_key_exists('open', $day) || array_key_exists('close', $day)) {
                foreach ($day as &$time) {
                    $time = $this->validateTime($time);
                }

                // if not both open and close, inject the other
                if (array_key_exists('open', $day) === false || empty($day['open'])) {
                    $day['open'] = '0:00';
                } elseif (array_key_exists('close', $day) === false || empty($day['close'])) {
                    $day['close'] = '23:59';
                }
            } else {
            // this is a day that has multiple open and close times
                foreach ($day as &$day2) {
                    foreach ($day2 as &$time) {
                        $time = $this->validateTime($time);
                    }

                    // if not both open and close, inject the other
                    if (array_key_exists('open', $day2) === false || empty($day2['open'])) {
                        $day2['open'] = '0:00';
                    } elseif (array_key_exists('close', $day2) === false || empty($day2['close'])) {
                        $day2['close'] = '23:59';
                    }
                }
            }
        }

        $this->hours = $hours;
    }

    /**
     * Retrieve the hours for the given day. If a day is not specified, today is
     * assumed.
     * @param  string $day='' Day of the week (title case)
     * @return array Array containing the open and close times for the given
     * day. Returns null if closed all day.
     */
    public function getHours($day='')
    {
        // find out what day we are looking for
        $dayofweek = date('l'); // default to today
        if (!empty($day)) {
            // given day isn't a normal day of the week, so try to parse it
            $dayofweek = date('l', strtotime($day));
            if ($dayofweek === false) {
                throw new Exception('Invalid day specified.');
            }
        }

        // lookup hours for the given day
        if (!empty($this->hours[$dayofweek])) {
            return (array)$this->hours[$dayofweek];
        }

        return null;
    }

    protected function validateTime($time) {
        // translate am/pm to 24 hour clock
        if (stripos($time, 'am') !== false) {
            $time = str_ireplace(array('am', ' '), '', $time);
        } elseif (stripos($time, 'pm') !== false) {
            $time = str_ireplace(array('pm', ' '), '', $time);
            list($h, $m) = explode(':', $time);
            $h += 12; // add 12 hours
            $time = $h . ':' . $m;
        }

        // enforce limits (@TODO be nice and correct? or throw vaidation errors?)
        list($h, $m) = explode(':', $time);
        if ($h >= 24) {
            $h = 23;
            $m = 59;
        } elseif ($h < 0) {
            $h = $m = 0;
        }
        if ($m >= 60) {
            $m = 59;
        }
        $time = $h . ':' . $m;

        return $time;
    }
}