<?php

require_once 'Abstract.php';

class Schedule_Holidays_Array extends Schedule_HolidaysAbstract
{

    /**
     * Load an array of holiday hours into the Holidays class
     *
     * @param array $holidayhours An array of hours opened and closed organized by day
     * of the week
     */
    public function __construct($holidayhours='')
    {
        if (empty($holidayhours)) return;

        // validate
        if (!is_array($holidayhours)) {
            throw new Exception('Invalid holiday hours were given.');
        }

        foreach ($holidayhours as $key=>$date) {
            // ensure that $date is in the proper date format
            $formatted_date = date(self::DATE_FORMAT, strtotime($key));
            if ($formatted_date === false) {
                throw new Exception('Invalid date format found.');
            }
            if ($formatted_date !== $key) {
                $holidayhours[$formatted_date] = $date;
                unset($holidayhours[$key]);
            }
        }

        $this->holidayhours = $holidayhours;

        parent::__construct($this->holidayhours);
    }

}