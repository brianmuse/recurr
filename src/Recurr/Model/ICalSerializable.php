<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

/**
 * An iCal property that helps define when an event recurs.
 */
interface ICalSerializable
{
    public function iCalSerialize();

    public static function iCalDeserialize($ical_string);
}
