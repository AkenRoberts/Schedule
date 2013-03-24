<?php

require_once 'Interface.php';

abstract class Schedule_HolidaysAbstract implements Schedule_HolidaysInterface
{

    const DATE_FORMAT = 'Y-m-d';

    /**
     * Container for the holidayhours
     *
     * @var array
     */
    protected $holidayhours = array();

    /**
     * [__construct description]
     */
    public function __construct($holidayhours='')
    {
        if (empty($holidayhours)) return;

        // validate
        foreach ($holidayhours as &$date) {
            // unlike the Hours class, if a date isn't given here, we don't assume
            // that they are closed, however, if the date is **empty**, we do.
            if (empty($date) && !is_array($date)) {
                $date = array(); // closed
            }

            // validate all open and close strings
            if (array_key_exists('open', $date) || array_key_exists('close', $date)) {
                foreach ($date as &$time) {
                    $time = $this->validateTime($time);
                }

                // if not both open and close, inject the other
                if (array_key_exists('open', $date) === false || empty($date['open'])) {
                    $date['open'] = '0:00';
                } elseif (array_key_exists('close', $date) === false || empty($date['close'])) {
                    $date['close'] = '23:59';
                }
            } else {
            // this is a day that has multiple open and close times
                foreach ($date as &$date2) {
                    foreach ($date2 as &$time) {
                        $time = $this->validateTime($time);
                    }

                    // if not both open and close, inject the other
                    if (array_key_exists('open', $date2) === false || empty($date2['open'])) {
                        $date2['open'] = '0:00';
                    } elseif (array_key_exists('close', $date2) === false || empty($date2['close'])) {
                        $date2['close'] = '23:59';
                    }
                }
            }
        }

        $this->holidayhours = $holidayhours;
    }

    /**
     * Determine if the given date has holiday hours.
     * @param  string  $date=''
     * @return boolean
     */
    public function hasHoliday($date='') {
        $holidayhours = $this->getHolidayHours($date);
        return (!is_null($holidayhours)) ? true : false;
    }

    /**
     * Retrieve the special holiday hours for the given day. If a day is not
     * specified, today is assumed.
     * @param  string $date='' Specific date in question (default is today)
     * @return array Array containing the open and close times for the given
     * day. Returns null if there are no holiday hours.
     */
    public function getHolidayHours($date='')
    {
        // find out what day we are looking for
        $holidaydate = date(self::DATE_FORMAT); // default to today
        if (!empty($date)) {
            $holidaydate = date(self::DATE_FORMAT, strtotime($date));
            if ($holidaydate === false) {
                throw new Exception('Invalid date specified.');
            }
        }

        // lookup hours for the given day
        if (!empty($this->holidayhours[$holidaydate])) {
            return (array)$this->holidayhours[$holidaydate];
        }

        return null;
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