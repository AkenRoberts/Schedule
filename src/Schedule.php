<?php namespace Cryode\Schedule;

use DateTime;

class Schedule
{
    protected $week;

    /**
     * Current time.
     *
     * @var DateTime
     */
    protected $now;

    /**
     * Instantiate Schedule.
     *
     * @param  DateTime  $now
     */
    public function __construct(Week $week, DateTime $now = null)
    {
        $this->week = $week;

        if (is_null($now))
        {
            $now = new DateTime('now', new \DateTimeZone('America/Chicago'));
        }

        $this->setNow($now);
    }

    public function getNow()
    {
        return $this->now;
    }

    public function setNow(DateTime $now)
    {
        $this->now = $now;
    }

    public function isOpen()
    {
        $today = $this->week->getWeekday($this->now->format('N'));

        list($openHour, $openMinute) = explode(':', $today->getOpen());
        $open = clone $this->now;
        $open->setTime($openHour, $openMinute);

        list($closeHour, $closeMinute) = explode(':', $today->getClose());
        $close = clone $this->now;
        $close->setTime($closeHour, $closeMinute);

        return ($this->now > $open && $this->now < $close);
    }

    public function isClosed()
    {
        return ! $this->isOpen();
    }

    public function getNextOpen()
    {
        //
    }

    public function getNextClosed()
    {
        //
    }
}
