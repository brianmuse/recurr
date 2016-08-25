<?php
/**
 * Robin API
 *
 * @copyright 2016 Robin Powered, Inc.
 * @link https://robinpowered.com/
 */

namespace Robin\Api\Libraries\Calendar\Model\ICal;

/**
 * The interface for an iCal parameter.
 *
 * @see https://tools.ietf.org/html/rfc5545#section-3.2
 */
interface Parameter extends ICalSerializable
{
    /**
     * Returns the parameter name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the parameter value.
     *
     * @return Value
     */
    public function getValue();
}
