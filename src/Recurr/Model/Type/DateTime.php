<?php

namespace Recurr\Model\Type;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Defines a calendar date and time of day.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.5
 */
final class DateTime
{
    /**
     * @var Date The date.
     */
    private $date;

    /**
     * @var Time The time.
     */
    private $time;

    /**
     * @var DateTimeImmutable
     */
    private $wrapped_php_datetime;

    public function __construct(Date $date, Time $time)
    {

        $this->date = $date;
        $this->time = $time;
    }

    /**
     * Gets the date.
     *
     * @return Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Gets the time.
     *
     * @return Time
     */
    public function getTime()
    {
        return $this->time;
    }

    public function toDateTimeImmutable()
    {
        if (null === $this->wrapped_php_datetime) {
            $this->wrapped_php_datetime = new DateTimeImmutable();
            $this->wrapped_php_datetime->setTimestamp(mktime(
                $this->time->getHour(),
                $this->time->getMinute(),
                $this->time->getSecond(),
                $this->date->getMonth(),
                $this->date->getDay(),
                $this->date->getYear()
            ));

            // No support for the concept of "local time". Default PHP TZ is UTC.
            $this->wrapped_php_datetime->setTimezone(new DateTimeZone($this->time->getTzid() ?: DateTimeZone::UTC));
        }

        return $this->wrapped_php_datetime;
    }

    public function __toString()
    {
        $time_string = (string) $this->time;

        if (!empty($this->time->getTzid())) {
            $parts = explode(':', $time_string);
            $time_string = $parts[1];
            $tzid = $parts[0];
        }

        return (isset($tzid) ? $tzid . ':' : '') . (string) $this->date . 'T' . $time_string;
    }
}
