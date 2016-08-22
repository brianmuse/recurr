<?php

namespace Recurr;

use InvalidArgumentException;

/**
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.3.10
 */
final class Recur
{
    const FREQ = "FREQ";
    const UNTIL = "UNTIL";
    const COUNT = "COUNT";
    const INTERVAL = "INTERVAL";
    const BYSECOND = "BYSECOND";
    const BYMINUTE = "BYMINUTE";
    const BYHOUR = "BYHOUR";
    const BYDAY = "BYDAY";
    const BYMONTHDAY = "BYMONTHDAY";
    const BYYEARDAY = "BYYEARDAY";
    const BYWEEKNO = "BYWEEKNO";
    const BYMONTH = "BYMONTH";
    const BYSETPOS = "BYSETPOS";
    const WKST = "WKST";

    /**
     * @var string
     */
    private $frequency;

    /**
     * @var DateTime
     */
    private $until;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $interval;

    /**
     * @var int[]
     */
    private $bysecond;

    /**
     * @var int[]
     */
    private $byminute;

    /**
     * @var int[]
     */
    private $byhour;

    /**
     * @var WeekDay[]
     */
    private $byday;

    /**
     * @var int[]
     */
    private $bymonthday;

    /**
     * @var int[]
     */
    private $byyearday;

    /**
     * @var int[]
     */
    private $byweekno;

    /**
     * @var int[]
     */
    private $bymonth;

    /**
     * @var int[]
     */
    private $bysetpos;

    /**
     * @var WeekDay[]
     */
    private $wkst;

    /**
     * Constructor
     *
     * @param string $recur A string representation of the `RECUR` value.
     */
    public function __construct($recur)
    {
        $this->wkst = new WeekDay(WeekDay::MONDAY);
        $this->hydrate($recur);
    }

    /**
     * Gets the frequency (FREQ).
     *
     * @return string
     */
    public function getFreq()
    {
        return $this->frequency;
    }

    /**
     * Gets the end date (UNTIL).
     *
     * @return DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * Gets the total amount of recurrences (COUNT).
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Gets the interval (INTERVAL).
     *
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Gets the seconds (BYSECONDS).
     *
     * @return int[]
     */
    public function getBySecond()
    {
        return $this->bysecond;
    }

    /**
     * Gets the minutes (BYMINUTES).
     *
     * @return int[]
     */
    public function getByMinute()
    {
        return $this->byminute;
    }

    /**
     * Gets the hours (BYHOUR).
     *
     * @return int[]
     */
    public function getByHour()
    {
        return $this->byhour;
    }

    /**
     * Gets the week days (BYDAY).
     *
     * @return WeekDay[]
     */
    public function getByDay()
    {
        return $this->byday;
    }

    /**
     * Gets the month days (BYMONTHDAY).
     *
     * @return int[]
     */
    public function getByMonthDay()
    {
        return $this->bymonthday;
    }

    /**
     * Gets the year days (BYYEARDAY).
     *
     * @return int[]
     */
    public function getByYearDay()
    {
        return $this->byyearday;
    }

    /**
     * Gets the week numbers (BYWEEKNO).
     *
     * @return int[]
     */
    public function getByWeekNo()
    {
        return $this->byweekno;
    }

    /**
     * Gets the months (BYMONTH).
     *
     * @return int[]
     */
    public function getByMonth()
    {
        return $this->bymonth;
    }

    /**
     * Gets the set positions (BYSETPOS).
     *
     * @return int[]
     */
    public function getBysetPos()
    {
        return $this->bysetpos;
    }

    /**
     * Gets the week start day (WKST).
     *
     * @return WeekDay[]
     */
    public function getWkst()
    {
        return $this->wkst;
    }

    /**
     * Populate the object from the RECUR value.
     *
     * @param string $recur The iCal string representation.
     * @return $this
     */
    private function hydrate($recur)
    {
        $recur = strtoupper($recur);
        $recur = trim($recur, ';');
        $pieces = explode(';', $recur);
        $parts  = [];

        if (empty($pieces)) {
            throw new InvalidArgumentException('The RECUR string has no parts.');
        }

        foreach ($pieces as $piece) {
            if (false === strpos($piece, '=')) {
                throw new InvalidArgumentException('The RECUR string value is malformed. ');
            }

            list($key, $val) = explode('=', $piece);
            $parts[$key] = $val;
        }

        if (!isset($parts[self::FREQ]) || !defined('Frequency::' . $parts[self::FREQ])) {
            throw new InvalidArgumentException('The FREQ part is missing or invalid.');
        } else {
            $this->frequency = (constant('Frequency::' . $parts[self::FREQ]));
        }

        if (isset($parts[self::UNTIL]) && isset($parts[self::COUNT])) {
            throw new InvalidArgumentException('The RECUR may not have both UNTIL and COUNT parts.');
        } elseif (isset($parts[self::UNTIL])) {
            $until = $parts[self::UNTIL];

            if (0 !== strrpos($until, 'T'))
            {
                $parts = explode('T', $until);
                $this->until = new DateTime(new Date($parts[0]), new Time($parts[1]));
            } else {
                $this->until = new Date($until);
            }
        } elseif (isset($parts[self::COUNT])) {
            $this->count = (int) $parts[self::COUNT];
        }

        if (isset($parts[self::INTERVAL])) {
            $this->interval = (int) $parts[self::INTERVAL];
        }

        if (isset($parts[self::BYSECOND])) {
            $this->bysecond = array_map(function ($second) {
                if ($second < 0 || $second > 60) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYSECOND part.');
                }

                return (int) $second;
            }, explode(',', $parts[self::BYSECOND]));
        }

        if (isset($parts[self::BYMINUTE])) {
            $this->byminute = array_map(function ($minute) {
                if ($minute < 0 || $minute > 59) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYMINUTE part.');
                }
                return (int) $minute;
            }, explode(',', $parts[self::BYMINUTE]));
        }

        if (isset($parts[self::BYHOUR])) {
            $this->byhour = array_map(function ($hour) {
                if ($hour < 0 || $hour > 23) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYHOUR part.');
                }

                return (int) $hour;
            }, explode(',', $parts[self::BYHOUR]));
        }

        if (isset($parts[self::BYDAY])) {
            $this->byday = array_map(function ($weekday) {
                return new WeekDay($weekday);
            }, explode(',', $parts[self::BYDAY]));
        }

        if (isset($parts[self::BYMONTHDAY])) {
            $this->bymonthday = array_map(function ($monthday) {
                if ($monthday < -31 || $monthday > 31 || 0 === $monthday) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYMONTHDAY part.');
                }

                return (int) $monthday;
            }, explode(',', $parts[self::BYMONTHDAY]));
        }

        if (isset($parts[self::BYYEARDAY])) {
            $this->byyearday = array_map(function ($yearday) {
                if ($yearday < -366 || $yearday > 366 || 0 === $yearday) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYYEARDAY part.');
                }

                return (int) $yearday;
            }, explode(',', $parts[self::BYYEARDAY]));
        }

        if (isset($parts[self::BYWEEKNO])) {
            $this->byweekno = array_map(function ($weekno) {
                if ($weekno < -53 || $weekno > 53 || 0 === $weekno) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYWEEKNO part.');
                }

                return (int) $weekno;
            }, explode(',', $parts[self::BYWEEKNO]));
        }

        if (isset($parts[self::BYMONTH])) {
            $this->bymonth = array_map(function ($month) {
                if ($month < 1 || $month > 12) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYMONTH part.');
                }

                return (int) $month;
            }, explode(',', $parts[self::BYMONTH]));
        }

        if (isset($parts[self::BYSETPOS])) {
            $this->bysetpos = array_map(function ($setpos) {
                if ($setpos < -366 || $setpos > 366 || 0 === $setpos) {
                    throw new InvalidArgumentException('The RECUR contains an invalid BYSETPOS part.');
                }

                return (int) $setpos;
            }, explode(',', $parts[self::BYSETPOS]));
        }

        if (isset($parts[self::WKST])) {
            $this->wkst = new Weekday($parts[self::WKST]);
        }
    }

    public function __toString()
    {
        $string = self::FREQ . '=' . $this->frequency . ';';

        if (!empty($this->until)) {
            $string .= self::UNTIL . '=' . (string) $this->until . ';';
        }

        if (!empty($this->count)) {
            $string .= self::COUNT . '=' . $this->count . ';';
        }

        if (!empty($this->interval)) {
            $string .= self::INTERVAL . '=' . $this->interval . ';';
        }

        if (!empty($this->bysecond)) {
            $string .= self::BYSECOND . '=' . implode(',', $this->bysecond) . ';';
        }

        if (!empty($this->byminute)) {
            $string .= self::BYMINUTE . '=' . implode(',', $this->byminute) . ';';
        }

        if (!empty($this->byhour)) {
            $string .= self::BYHOUR . '=' . implode(',', $this->byhour) . ';';
        }

        if (!empty($this->byday)) {
            $string .= self::BYDAY . '=' . implode(',', $this->byday) . ';';
        }

        if (!empty($this->bymonthday)) {
            $string .= self::BYMONTH . '=' . implode(',', $this->bymonth) . ';';
        }

        if (!empty($this->byyearday)) {
            $string .= self::BYYEARDAY . '=' . implode(',', $this->byyearday) . ';';
        }

        if (!empty($this->byweekno)) {
            $string .= self::BYWEEKNO . '=' . implode(',', $this->byweekno) . ';';
        }

        if (!empty($this->bymonth)) {
            $string .= self::BYMONTH . '=' . implode(',', $this->bymonth) . ';';
        }

        if (!empty($this->bysetpos)) {
            $string .= self::BYSETPOS . '=' . implode(',', $this->bysetpos) . ';';
        }

        if (!empty($this->wkst)) {
            $string .= self::WKST . '=' . (string) $this->wkst . ';';
        }

        return trim($string, ';');
    }
}
