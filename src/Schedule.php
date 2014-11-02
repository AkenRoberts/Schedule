<?php namespace Cryode\Schedule;

use DateTime;

class Schedule
{
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
