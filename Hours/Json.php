<?php

require_once 'Abstract.php';

class Schedule_Hours_Json extends Schedule_HoursAbstract
{

    /**
     * Load a JSON object of hours into the Hours class
     *
     * @param string $hours Path to the JSON file containing all of the hours opened
     * and closed organized by day of the week
     */
    public function __construct($hours)
    {
        $filename = $hours;
        $hours = null;

        // validate
        if (empty($filename)) {
            throw new Exception('No hours were given.');
        }
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new Exception('File provided was not readable. Please check permissions.');
        }

        // open file and parse it
        $hours = file_get_contents($filename);
        $hours = json_decode($hours, true);
        if (!$hours) {
            throw new Exception('Invalid JSON received.');
        }

        $this->hours = $hours;

        parent::__construct($this->hours);
    }

}