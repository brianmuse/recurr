<?php

namespace Recurr\Model\Type;

use DateTimeImmutable;
use InvalidArgumentException;
use Robin\Api\Libraries\Calendar\Model\ICal\Value;

/**
 * Defines a time of day.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.12
 */
final class Time implements Value
{
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

    public function __construct($hour, $minute, $second, $is_utc = null)
    {
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->is_utc = $is_utc;
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

    public function iCalSerialize()
    {
        return $this->hour . $this->minute . $this->second . ($this->is_utc ? 'Z' : '');
    }

    public static function iCalDeserialize($ical_string)
    {
        if ('Z' === substr($ical_string, -1, 1)) {
            $is_utc = true;
            $time = DateTimeImmutable::createFromFormat(self::UTC_TIME_PATTERN, $ical_string);
        } else {
            $is_utc = false;
            $time = DateTimeImmutable::createFromFormat(self::FLOATING_TIME_PATTERN, $ical_string);
        }

        if (false === $time) {
            throw new InvalidArgumentException('The time was malformed: ' . $ical_string);
        }

        return static(
            $time->format('Y'),
            $time->format('m'),
            $time->format('d'),
            $is_utc
        );
    }
}
