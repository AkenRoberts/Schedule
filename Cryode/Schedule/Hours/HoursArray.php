<?php namespace Cryode\Schedule\Hours;

/**
 * Array implementation for Hours.
 */
class HoursArray extends HoursAbstract
{
    /**
     * Format raw hours content into appropriate array.
     *
     * @param  array $hours
     * @return array
     */
    protected function format($hours)
    {
        // Ensure array format (duh).
        if ( ! is_array($hours))
        {
            throw new \InvalidArgumentException('HoursArray expects an array of data.');
        }
        // No hours kind of defeats the purpose of this. *COULD* be optional
        // to some people, though. Probably good practice to prevent empties, though.
        else if (empty($hours))
        {
            throw new \Exception('The hours array must not be empty.');
        }

        return $hours;
    }
}