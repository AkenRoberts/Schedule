<?php

interface Schedule_HolidaysInterface {

    /**
     * Load the holidays hours
     * @param mixed $holidayhours
     */
    public function __construct($holidayhours='');

    /**
     * Determine if the given date is considered a holdiay.
     * @param  string $date
     * @return boolean
     */
    public function hasHoliday($date);

    /**
     * Get special holiday hours for a given date if they exist.
     * @param  string $date
     * @return array  Open and close hours for the given day
     */
    public function getHolidayHours($date);
}