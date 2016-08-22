<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal\Recur;

/**
 * Constants for RRULE day values.
 */
class Day
{
    /**
     * RRULE Monday value.
     *
     * @type string
     */
    const MONDAY = 'MO';

    /**
     * RRULE Tuesday value.
     *
     * @type string
     */
    const TUESDAY = 'TU';

    /**
     * RRULE Wednesday value.
     *
     * @type string
     */
    const WEDNESDAY = 'WE';

    /**
     * RRULE Thursday value.
     *
     * @type string
     */
    const THURSDAY = 'TH';

    /**
     * RRULE Friday value.
     *
     * @type string
     */
    const FRIDAY = 'FR';

    /**
     * RRULE Saturday value.
     *
     * @type string
     */
    const SATURDAY = 'SA';

    /**
     * RRULE Sunday value.
     *
     * @type string
     */
    const SUNDAY = 'SU';

    /**
     * The separator used in iCal RRULE BYDAY values to seperate multiple days.
     *
     * eg. 'SU,MO,TU'
     *
     * @type string
     */
    const RRULE_DAY_SEPERATOR = ',';

    /**
     * A regex string that may be used to split a `BYDAY` RRULE value into it's parts.
     *
     * The first part, if it exists, is the offset value. The second part is the day string.
     *
     * For example, this will parse the parts of the "last sunday of the month":
     *
     * ```PHP
     * $day_parts = [];
     * preg_match(Day::OFFSET_DAY_PARSING_REGEX, '-1SU', $day_parts);
     * $offset = $day_parts[1]; // '-1'
     * $day = $day_parts[2]; // 'SU'
     * ```
     *
     * @type string
     */
    const OFFSET_DAY_PARSING_REGEX = '/^([+-]?[0-9]+)([A-Z]{2})$/';
}
