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
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Defines a precise period of time.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.9
 */
final class Period
{

    /**
     * @var DateTime The start of the period.
     */
    private $start;

    /**
     * @var DateTime The end of the period.
     */
    private $end;

    /**
     * @var string Whether the format of the period is `period-explicit` or `period-start` (using a duration).
     */
    private $is_explicit;

    public function __construct(DateTime $start, DateTime $end = null, DateInterval $duration = null)
    {
        $this->start = $start;

        if (null === $end && null === $duration) {
            throw new InvalidArgumentException('The period must contain and end or a duration.');
        } elseif (null === $end) {
            $end = $start->toDateTimeImmutable()->add($duration);
            $this->end = new DateTime()
        }

        $this->end = $end;
    }

    public static function fromString($period)
    {

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
