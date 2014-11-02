<?php namespace Cryode\Schedule\Weekday;

/**
 * WeekdayProvider acts as a repository for standard weekday hours of operation.
 */
interface WeekdayProvider
{
    public function getWeek();
}
