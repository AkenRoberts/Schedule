<?php namespace Cryode\Schedule;

class Week extends \ArrayObject
{
    public function getWeekday($day)
    {
        return $this->offsetGet($day);
    }

    /**
     * @todo This method will check the $start day as part of its routine.
     *       If the current day has already closed, that day will still be
     *       returned as the "next open day". Need to take current day's hours
     *       into consideration when calculating this.
     */
    public function getNextOpenDay(\DateTime $start)
    {
        $end = clone $start;
        $end->modify('+7 days');

        $iterator = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        foreach ($iterator as $date)
        {
            $weekday = $this->offsetGet($date->format('N'));

            if ( ! is_null($weekday->getOpen()))
            {
                return $weekday;
            }
        }

        throw new \Exception('Next open day not found.');
    }
}
