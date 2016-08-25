<?php

namespace Recurr\Model\Type;

use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Defines a calendar date.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.4
 */
final class Date
{
    const DATE_PATTERN = 'Ymd';

    /**
     * @var string The four-digit year (0000-9999).
     */
    private $year;

    /**
     * @var int The two-digit month of the year (01-12).
     */
    private $month;

    /**
     * @var int The two-digit day of the month (01-31 with the upper bound depending on the month and year).
     */
    private $day;

    public function __construct($year, $month, $day)
    {
        if ((int) $year < 0 || (int) $year > 9999) {
            throw new InvalidArgumentException('The year must be 0000 to 9999.');
        }

        if ((int) $month < 1 || (int) $month > 12) {
            throw new InvalidArgumentException('The month must be 01 to 12.');
        }

        if ((int) $day < 1 || (int) $day > 31) {
            throw new InvalidArgumentException('The day must be 01 to 31.');
        }

        // Ensure the proper iCal formatting
        $this->year = str_pad($year, 4, '0', STR_PAD_LEFT);
        $this->month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $this->day = str_pad($day, 2, '0', STR_PAD_LEFT);
    }

    public static function fromString($date)
    {
        $date = DateTimeImmutable::createFromFormat(self::DATE_PATTERN, $date);

        return static(
            $date->format('Y'),
            $date->format('m'),
            $date->format('d')
        );
    }

    /**
     * Gets the four-digit year (0000-9999).
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Gets the two-digit month of the year (01-12).
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Gets the two-digit day of the month (01-31 with the upper bound depending on the month and year).
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    public function __toString()
    {
        return $this->year . $this->month . $this->day;
    }
}
