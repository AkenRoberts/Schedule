<?php

namespace Cryode\Schedule\Hours;

/**
 * Abstract class for Hours implementors in different formats.
 *
 * @todo Add support for objects in hours, maybe?
 */
abstract class HoursAbstract
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
    protected $weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    public function __construct($hours)
    {
        if (empty($hours))
        {
            throw new \InvalidArgumentException('Hours data must not be empty.');
        }

        $this->hours = $this->format($hours);

        $this->validateHours($this->hours);
    }

    /**
     * Retrieve the hours for the given day. If a day is not specified, today is
     * assumed.
     *
     * @param  string $day Day of the week (title case)
     * @return mixed
     */
    public function getHours($day = null)
    {
        // What day are we looking for?
        if ($day !== null && ! in_array($day, $this->weekdays))
        {
            throw new \InvalidArgumentException("Invalid week day supplied to getHours(): {$day}");
        }
        else
        {
            // No day specified -- assume today.
            $day = date('l');
        }

        // Return this day's hours, or false if none are specified.
        if ( ! isset($this->hours[$day]))
        {
            return false;
        }

        return $this->hours[$day];
    }

    /**
     * Sanitize and format the raw data.
     * Final array structure should be:
     *
     * array(
     *   'Sunday' => array(
     *     'open' => '8:00',
     *     'close' => '20:00'
     *   ),
     *   ...
     * )
     *
     * The week days should be their full, capitalized names.
     * Hours are done in 12 or 24 hour formats. Valid examples include:
     * 3am
     * 11:02 pm
     * 23:59
     * 16
     * 0
     *
     * @param  mixed $hours
     * @return array
     */
    abstract protected function format($hours);

    /**
     * Validate the formatted data to ensure no weird
     * times or structures.
     *
     * @param  array $hours
     * @return boolean
     */
    protected function validateHours($hours)
    {
        // Check if the hours is an array to begin with, and has at least one value.
        if ( ! is_array($hours))
        {
            throw new \InvalidArgumentException('Invalid Hours: Non-array value.');
        }

        if (empty($hours))
        {
            throw new \InvalidArgumentException('Invalid Hours: No hour data. Must have at least one defined day / hours set.');
        }

        // Loop through the values and check for proper format.
        foreach ($hours as $weekday => $oc)
        {
            // Is this a valid weekday?
            if ( ! in_array($weekday, $this->weekdays))
            {
                throw new \UnexpectedValueException("Invalid weekday: '{$weekday}' not recognized.");
            }

            // Do our open and close keys exist?
            if ( ! array_key_exists('open', $oc) OR ! array_key_exists('close', $oc))
            {
                throw new \Exception('Invalid time definition: Each weekday should have an "open" and "close" key/value pair.');
            }

            // Are the open/close times valid?
            foreach (array('open', 'close') as $ocCheck)
            {
                if ( ! $this->validateTime($oc[$ocCheck]))
                {
                    throw new \Exception("Invalid '{$ocCheck}' time value: {$oc[$ocCheck]}");
                }
            }
        }

        // If we got this far, everything is okay!
        return true;
    }

    /**
     * Validate a time string.
     *
     * @param  mixed $time
     * @return boolean
     */
    protected function validateTime($time)
    {
        return (bool) preg_match('/^([01]?[0-9]|2[0123])(\:[0-5][0-9])?(?:\s?(?<!0|1[3-9])(am|pm))?$/i', $time);
    }

    /*
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
        foreach ($this->weekdays as $dayofweek) {
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
    */

    /*
     * Validates a given time string to be in the proper 24 hour clock notation.
     * Currently this method returns a modified version of the string if it
     * detects a problem with the formatting.
     * @param  string $time
     * @return string
     *
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
    */
}