<?php namespace Cryode\Schedule\Hours;

interface HoursInterface
{
    /**
     * Load the hours
     *
     * @param mixed $hours
     */
    public function __construct($hours);

    /**
     * Retrieve the hours of operation. If a day is not given, we'll default to
     * today, otherwise, we'll try to parse it and return the correct hours.
     *
     * @param  string $day Day of the week to get the hours for
     * @return array  Open and close hours for the given day
     */
    public function getHours($day='');
}