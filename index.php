<?php

require_once 'Schedule.php';

class Schedule_HoursInterface {

	public $hours = array(
			'Sunday'	=> array(
				'open'	=> '0:00',
				'close'	=> '1:00',
			),
			'Monday'	=> array(
				'open'	=> '8:00',
				'close'	=> '20:00',
			),
			'Tuesday'	=> array(
				array(
					'open'	=> '8:00',
					'close'	=> '12:00',
				),
				array(
					'open'	=> '14:00',
					'close'	=> '20:00',
				),
			),
			'Wednesday'	=> false,
			'Thursday'	=> false,
			'Friday'	=> false,
			'Saturday'	=> array(
				'open'	=> '10:00',
				'close'	=> '14:00',
			),
		);

	public function getHours($day = null)
	{
		if ($day !== null AND array_key_exists($day, $this->hours))
		{
			return $this->hours[$day];
		}

		return $this->hours;
	}
}

class Schedule_HolidayInterface {}

$schedule = new Schedule(new Schedule_HoursInterface, new Schedule_HolidayInterface);

var_dump($schedule->nextOpen(), $schedule->nextClosed());