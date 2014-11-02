<?php namespace Cryode\Schedule\Weekday;

use Cryode\Schedule\Week;

class ArrayProvider implements WeekdayProvider
{
    /**
     * Hardcoded set of standard weekly hours.
     *
     * @var array
     */
    protected $hours = [
        1 => ['11:00', '21:00'],
        2 => ['11:00', '21:00'],
        3 => ['11:00', '21:00'],
        4 => ['11:00', '21:00'],
        5 => ['11:00', '22:00'],
        6 => ['11:00', '22:00'],
        7 => ['16:00', '21:00'],
    ];

    public function getWeek()
    {
        $days = [];

        foreach ($this->hours as $day => list($open, $close))
        {
            $days[$day] = new Weekday($day, $open, $close);
        }

        return new Week($days);
    }
}
