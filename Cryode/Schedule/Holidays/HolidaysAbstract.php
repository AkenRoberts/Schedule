<?php namespace Cryode\Schedule\Holidays;

abstract class HolidaysAbstract implements HolidaysInterface
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
    public function __construct($holidayhours)
    {
        if (empty($holidayhours)) return;

        // validate
        foreach ($holidayhours as &$date_wrapper) {
            // If an empty date is given here, they are closed
            if (empty($date_wrapper) || !is_array($date_wrapper)) {
                $date_wrapper = array(array()); // closed
            } elseif (!empty($date_wrapper) && (array_key_exists('open', $date_wrapper) || array_key_exists('close', $date_wrapper))) {
                $date_wrapper = array($date_wrapper);
            }
        }

        foreach ($holidayhours as &$date_wrapper) {
            foreach ($date_wrapper as &$date_hours) {
                foreach ($date_hours as &$time) {
                    $time = $this->validateTime($time);
                }

                // if not both open and close, inject the other
                if (!array_key_exists('open', $date_hours) || empty($date_hours['open'])) {
                    $date_hours['open'] = '0:00';
                }
                if (!array_key_exists('close', $date_hours) || empty($date_hours['close'])) {
                    $date_hours['close'] = '23:59';
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
                throw new \Exception('Invalid date specified.');
            }
        }

        // lookup hours for the given day
        if (!empty($this->holidayhours[$holidaydate])) {
            return (array)$this->holidayhours[$holidaydate];
        }

        return false;
    }

    /**
     * Export the entire hours array
     * @return array
     */
    public function toArray() {
        return (array)$this->holidayhours;
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