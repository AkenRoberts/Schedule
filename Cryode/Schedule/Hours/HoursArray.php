<?php namespace Cryode\Schedule\Hours;

class HoursArray extends HoursAbstract
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
        if (!is_array($hours)) {
            throw new \Exception('Invalid hours were given.');
        }
        if (empty($hours)) {
            throw new \Exception('No hours were given.');
        }

        $this->hours = $hours;

        parent::__construct($this->hours);
    }
}