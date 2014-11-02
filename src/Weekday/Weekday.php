<?php namespace Cryode\Schedule\Weekday;

class Weekday
{
    protected $weekday;
    protected $open;
    protected $close;

    public function __construct($weekday, $open, $close)
    {
        $this->weekday = $weekday;
        $this->open = $open;
        $this->close = $close;
    }

    public function getWeekday()
    {
        return $this->weekday;
    }

    public function getOpen()
    {
        return $this->open;
    }

    public function getClose()
    {
        return $this->close;
    }
}
