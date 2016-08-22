<?php

/*
 * Copyright 2013 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based on rrule.js
 * Copyright 2010, Jakub Roztocil and Lars Schoning
 * https://github.com/jkbr/rrule/blob/master/LICENCE
 */

namespace Recurr;
use DateTimeImmutable;

/**
 * Defines a time of day.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.12
 */
final class Time
{
    const TZID = 'TZID';

    const FLOATING_TIME_PATTERN = 'His';

    const UTC_TIME_PATTERN = 'His\z';

    /**
     * @var string The two-digit hour of the day (00-23).
     */
    private $hour;

    /**
     * @var string The two-digit minute of the hour (00-59).
     */
    private $minute;

    /**
     * @var string The two-digit second of the minute (00-60). A value of 60 represents a leap second.
     */
    private $second;

    /**
     * @var string The time zone ID (TZID).
     */
    private $tzid;

    /**
     * Whether the time is in UTC.
     *
     * If this value is false, it is said to be "floating" and are not
     * bound to any time zone in particular. They are used to represent
     * the same hour, minute, and second value regardless of which
     * time zone is currently being observed.
     *
     * @var bool
     */
    private $is_utc = false;

    public function __construct($hour, $minute, $second, $tzid = null)
    {
        if ('Z' === substr($time, -1, 1)) {
            $this->is_utc = true;
            $time = DateTimeImmutable::createFromFormat(self::UTC_TIME_PATTERN, $time);
        } else {
            $this->tzid = $tzid;
            $time = DateTimeImmutable::createFromFormat(self::FLOATING_TIME_PATTERN, $time);
        }

        $this->hour = $time->format('H');
        $this->minute = $time->format('i');
        $this->second = $time->format('s');
    }

    public static function fromString($time)
    {

        $date = DateTimeImmutable::createFromFormat(self::DATE_PATTERN, $date);

        return static(
            $date->format('Y'),
            $date->format('m'),
            $date->format('d')
        )
    }

    /**
     * Gets the two-digit hour of the day (00-23).
     *
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Gets the two-digit minute of the hour (00-59).
     *
     * @return string
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * Gets the two-digit second of the minute (00-60). A value of 60 represents a leap second.
     *
     * @return string
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Gets the time zone ID (TZID).
     *
     * @return string
     */
    public function getTzid()
    {
        return $this->tzid;
    }

    /**
     * Gets whether the time is UTC or local time.
     *
     * If this value is false, it is said to be "floating" and are not
     * bound to any time zone in particular. They are used to represent
     * the same hour, minute, and second value regardless of which
     * time zone is currently being observed.
     *
     * @return boolean
     */
    public function isUtc()
    {
        return $this->is_utc;
    }

    public function __toString()
    {
        return (null !== $this->tzid ? self::TZID . '=' . $this->tzid . ':' : '')  // Add TZID if given
            . $this->hour . $this->minute . $this->second // Add the formatted time
            . ($this->is_utc ? 'Z' : ''); // Add the 'Z' if it's a UTC time.
    }
}
