<?php

require_once 'Interface.php';

abstract class Schedule_HoursAbstract implements Schedule_HoursInterface
{

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
     * Receive an array of hours and do a thorough validation to make sure all
     * of the hours are there and they all make sense. When possible, we will
     * silently clean up the array.
     *
     * @TODO Cleanup/remove invalid days of the week? Currently they are ignored
     *
     * @TODO Create toArray method for dumping the entire hours array?
     *
     * @params array $hours
     * @return void
     */
    public function __construct($hours)
    {
        // force the array to be in the following format:
        // array(
        //     $dayofweek => array(
        //         array(
        //             'open'  => $time,
        //             'close' => $time
        //         )
        //     )
        // );
        foreach ($hours as &$day_wrapper) {
            if (empty($day_wrapper) || !is_array($day_wrapper)) {
                $day_wrapper = array(array()); // closed
            } elseif (array_key_exists('open', $day_wrapper) || array_key_exists('close', $day_wrapper)) {
                $day_wrapper = array($day_wrapper);
            }
        }

        // if a day isn't given, we assume closed
        foreach ($this->daysofweekArray as $dayofweek) {
            if (!isset($hours[$dayofweek]) || empty($hours[$dayofweek])) {
                $hours[$dayofweek] = array(array()); // closed
            }
        }

        // validate all open and close strings
        foreach ($hours as &$day_wrapper) {
            foreach ($day_wrapper as &$days_hours) {
                foreach ($days_hours as &$time) {
                    $time = $this->validateTime($time);
                }

                // if not both open and close, inject the other
                if (!array_key_exists('open', $days_hours) || empty($days_hours['open'])) {
                    $days_hours['open'] = '0:00';
                }
                if (!array_key_exists('close', $days_hours) || empty($days_hours['close'])) {
                    $days_hours['close'] = '23:59';
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
     * day. Returns false if closed all day.
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

        return false;
    }

    /**
     * Export the entire hours array
     * @return array
     */
    public function toArray() {
        return (array)$this->hours;
    }

    /**
     * Validates a given time string to be in the proper 24 hour clock notation.
     * Currently this method returns a modified version of the string if it
     * detects a problem with the formatting.
     * @param  string $time
     * @return string
     */
    protected function validateTime($time) {
        // translate am/pm to 24 hour clock
        if (stripos($time, 'am') !== false) {
            $time = str_ireplace(array('am', ' '), '', $time);
        } elseif (stripos($time, 'pm') !== false) {
            $time = str_ireplace(array('pm', ' '), '', $time);
            list($h, $m) = explode(':', $time);
            if ($h > 12) $h += 12; // add 12 hours if after 12pm
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