<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

use InvalidArgumentException;

/**
 * A registry of properties mapped to their associated classes.
 *
 * https://tools.ietf.org/html/rfc5545#section-8.3
 */
class Registries
{
    // TODO: Explore RANGE needed for RecurrenceId
    // TODO: Explore "sequence" and whether google or ms provides it. Explore decoding google ID to see if its UID.
    // TODO: look at how other libs handle casing for these classes. (ical4j)

    /**
     * Supported iCal properties.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-8.3.2
     */
    const PROPERTIES = [
        RecurrenceId::NAME => RecurrenceId::class,
        Exdate::NAME => Exdate::class,
        Exrule::NAME => Exrule::class,
        Rdate::NAME => Rdate::class,
        Rrule::NAME => Rrule::class,
    ];

    /**
     * Supported iCal parameters.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-8.3.3
     */
    const PARAMETERS = [
        Tzid::NAME => Tzid::class,
        Value::NAME => Value::class,
    ];

    /**
     * Supported iCal value types.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-8.3.4
     */
    const VALUE_TYPES = [
        Date::NAME => Date::class,
        DateTime::NAME => DateTime::class,
        Duration::NAME => Duration::class,
        Recur::NAME => Recur::class,
        Time::NAME => Time::class,
    ];
}
