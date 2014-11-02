<?php namespace Cryode\Schedule;

use DateTime;

class Schedule
{
    const SUNDAY    = 0;
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;

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
    public function __construct(DateTime $now = null)
    {
        $this->days = $days;

        if (is_null($now))
        {
            $now = new DateTime;
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
        return false;
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
