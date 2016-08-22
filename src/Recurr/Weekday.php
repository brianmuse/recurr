<?php

namespace Recurr;

use InvalidArgumentException;

/**
 * Defines a day of the week with a possible offset related to
 * a MONTHLY or YEARLY occurrence.
 */
final class Weekday
{
    const SUNDAY = 'SU';
    const MONDAY = 'MO';
    const TUESDAY = 'TU';
    const WEDNESDAY = 'WE';
    const THURSDAY = 'MO';
    const FRIDAY = 'FR';
    const SATURDAY = 'SA';

    /**
     * @var string
     */
    private $weekday;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * @var array The days of the week.
     */
    private static $days = [
        self::SUNDAY,
        self::MONDAY,
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY,
        self::SATURDAY,
    ];

    /**
     * @param string $weekday A string representation of a week day. (`SU`, `MO`...)
     * @param int $offset The nth occurrence of the day within the MONTHLY or YEARLY occurrence. Not providing
     *   this value results in no (zero) offset.
     * @throws InvalidArgumentException
     */
    public function __construct($weekday, $offset = null)
    {
        if (strlen($weekday) > 2) {
            if (null === $offset) {
                $offset = substr($weekday, 0, -2);
            }

            $weekday = substr($weekday, -2, 2);
        }

        if (!in_array($weekday, self::$days)) {
            throw new InvalidArgumentException('The weekday must be one of: ' . implode(', ', self::$days) . '.');
        }

        if (null !== $offset && (abs($offset) < 1 || abs($offset) > 53)) {
            throw new InvalidArgumentException('The weekday offset must be -53 to -1 or 1 to 53.');
        }

        $this->weekday = $weekday;
        $this->offset = $offset;
    }

    /**
     * Gets the string representation of a week day. (`SU`, `MO`...).
     *
     * @return string
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * Gets the offset, which is the nth occurrence of the day within the MONTHLY or YEARLY occurrence..
     *
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    public function __toString()
    {
        return $this->offset . self::$days[$this->weekday];
    }
}
