<?php

require_once 'Abstract.php';

class Schedule_Hours_Array extends Schedule_HoursAbstract
{

    /**
     * Load an array of hours into the Hours class
     *
     * @param array $hours An array of hours opened and closed organized by day
     * of the week
     */
    public function __construct($hours)
    {
        // validate
        if (empty($hours)) {
            throw new Exception('No hours were given.');
        }
        if (!is_array($hours)) {
            throw new Exception('Invalid hours were given.');
        }

        // if the day isn't given, assume closed
        foreach ($this->daysofweekArray as $dayofweek) {
            if (!isset($hours[$dayofweek]) || empty($hours[$dayofweek])) {
                $hours[$dayofweek] = array(); // closed
            }
        }

        $this->hours = $hours;

        parent::__construct($this->hours);
    }

}