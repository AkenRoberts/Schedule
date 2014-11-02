<?php namespace Cryode\Schedule;

class Week extends \ArrayObject
{
    public function getWeekday($day)
    {
        return $this->offsetGet($day);
    }
}
