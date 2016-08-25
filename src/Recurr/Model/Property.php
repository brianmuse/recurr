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
interface Property extends ICalSerializable
{
    /**
     * Returns the property name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the parameters for the property.
     *
     * @return Parameter[]
     */
    public function getParameterList();

    /**
     * Returns the property value.
     *
     * @return Value
     */
    public function getValue();
}
