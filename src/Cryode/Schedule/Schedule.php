<?php

namespace Cryode\Schedule;

/**
 * Schedule!
 *
 * @author  Eric "Aken" Roberts <eric@cryode.com>
 * @author  Brandon Kahre
 */
class Schedule extends \DateTime {

	/**
	 * @var
	 */
	public $hours;

	/**
	 * @var
	 */
	public $holidays;

	/**
	 * Define the hours and holidays dependencies.
	 *
	 * @param HoursAbstract   $hours
	 * @param HolidayAbstract $holidays
	 * @return void
	 */
	public function __construct(Hours\HoursAbstract $hours, Hours\HolidayAbstract $holidays = null)
	{
		parent::__construct();

		$this->hours = $hours;
		$this->holidays = $holidays;

		$this->hours->today = $this->hours->getHours($this->format('l'));
	}

	/**
	 * Is the business open today?
	 *
	 * @return boolean
	 */
	public function isOpen()
	{
		$hours = $this->hours->today;

		// Are we open at all?
		if ($hours === false)
		{
			return false;
		}

		if ( ! array_key_exists('open', $hours))
		{
			foreach ($hours as $hour_set)
			{
				if ($this->inRange($hour_set['open'], $hour_set['close']) === true)
				{
					return true;
				}
			}

			return false;
		}

		return $this->inRange($hours['open'], $hours['close']);
	}

	/**
	 * Is the business closed today?
	 *
	 * @return boolean
	 */
	public function isClosed()
	{
		return ( ! $this->isOpen());
	}

	/**
	 * Is today a holiday?
	 *
	 * @return boolean
	 */
	public function isHoliday()
	{
		return $this->holidays->isHoliday($this->format('Y-m-d'));
	}

	/**
	 * When is the next open date?
	 *
	 * @return string
	 */
	public function nextOpen()
	{
		return $this->nextCheck('open');
	}

	/**
	 * When is the next closed date?
	 *
	 * @return string
	 */
	public function nextClosed()
	{
		return $this->nextCheck('close');
	}

	/**
	 * The logic behind nextOpen() and nextClosed().
	 *
	 * @param  string $check open or close
	 * @return string        Date string
	 */
	protected function nextCheck($check)
	{
		if ( ! in_array($check, array('open', 'close')))
		{
			throw new \InvalidArgumentException('Schedule::nextCheck() requires an "open" or "close" value.');
		}

		$day = new \DateTime('now', $this->getTimezone());

		$iterations = 30;

		for ($i = 0; $i < $iterations; $i++)
		{
			// Holiday check goes here.

			$hours = $this->hours->getHours($day->format('l'));

			if ($hours === false)
			{
				if ($check === 'close')
				{
					return $day->format('c');
				}
			}
			else
			{
				// Does this day have multiple open/close times?
				if ( ! array_key_exists($check, $hours))
				{
					foreach ($hours as $hour_set)
					{
						list ($hour, $minute) = explode(':', $hour_set[$check]);

						$day->setTime($hour, $minute);

						if ($day > $this)
						{
							return $day->format('c');
						}
					}
				}
				else
				{
					list ($hour, $minute) = explode(':', $hours[$check]);

					$day->setTime($hour, $minute);

					if ($day > $this)
					{
						return $day->format('c');
					}
				}
			}

			$day->setTime($this->format('H'), $this->format('i'));
			$day->add(new \DateInterval('P1D'));
		}

		// If we got this far, there are no open dates
		// within the specified range.
		throw new \Exception("There are no {$check} dates in the next {$iterations} days.");
	}

	/**
	 * Determine if the current time is between
	 * the start and end times.
	 *
	 * @param  string $start hh:mm
	 * @param  string $end   hh:mm
	 * @return boolean
	 */
	public function inRange($start, $end)
	{
		$now = $this->format('U');
		$start = strtotime($start);
		$end = strtotime($end);

		return ($now >= $start && $now <= $end);
	}

	/**
	 * Wraps the native setTimezone method, allows you to
	 * specify just the timezone string in addition to a
	 * standard DateTimeZone object.
	 *
	 * @param  mixed $timezone DateTimeZone object, or timezone string
	 * @return mixed FALSE on failure, or $this for method chaining
	 */
	public function setTimezone($timezone)
	{
		if ( ! $timezone instanceof DateTimeZone) {
			$timezone = new \DateTimeZone($timezone);
		}

		parent::setTimezone($timezone);
	}
}