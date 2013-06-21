<?php namespace Cryode\Schedule\Holidays;

class HolidaysJson extends HolidaysAbstract
{
    /**
     * Load a JSON object of hours into the Holidays class
     *
     * @param $holidayhours Path to the JSON file containing all of the hours opened
     * and closed organized by day of the week
     */
    public function __construct($holidayhours='')
    {
        $filename = $holidayhours;
        $holidayhours = null;

       if (empty($filename)) return;

        // validate
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception('File provided was not readable. Please check permissions.');
        }

        // open file and parse it
        $holidayhours = file_get_contents($filename);
        $holidayhours = json_decode($holidayhours, true);
        if (!$holidayhours) {
            throw new \Exception('Invalid JSON received.');
        }

        $this->holidayhours = $holidayhours;

        parent::__construct($this->holidayhours);
    }
}