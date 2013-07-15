<?php

namespace Cryode\Schedule\Hours;

/**
 * JSON implementation for Hours.
 */
class HoursJson extends HoursAbstract
{
    /**
     * Format raw hours content into appropriate array.
     *
     * @param  JSON $hours
     * @return array
     */
    protected function format($hours)
    {
        if (($hours = json_decode($hours, true)) === false)
        {
            throw new \InvalidArgumentException('Could not properly decode JSON.');
        }

        return $hours;
    }
}